<?php
namespace App\Http\Controllers\Auth;

use App\Models\Cart;
use App\Models\CartProduct;
use Cookie;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    protected $redirectTo = '/user';

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return trans('user.failedLoginMessage');
    }

    /**
     * Create a new authentication controller instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard     $auth
     * @param \Illuminate\Contracts\Auth\Registrar $registrar
     */
    public function __construct(Guard $auth, Registrar $registrar)
    {
        $this->auth = $auth;
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($v->fails()) {
            return redirect($this->loginPath())
                ->withInput($request->only('email', 'remember'))
                ->withErrors($v->errors());
        }

        $credentials = $request->only('email', 'password');

        $credentials['isActive'] = 1;

        if ($this->auth->attempt($credentials, $request->has('remember'))) {
            $user = User::where('email', $request->get('email'))
                ->first();
            $user->touch();

            self::checkCookie();

            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPath())
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->registrar->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request,
                $validator
            );
        }
        $this->registrar->create($request->all());

        return $this->getMessage(trans('user.confirmation'));
    }


    /**
     * @param            $message
     * @param bool|false $redirect
     * @return \Illuminate\View\View
     */
    protected function getMessage($message, $redirect = false)
    {
        return view(
            'message',
            compact(
                'message',
                'redirect'
            )
        );
    }

    /**
     * @param null $provider
     * @return mixed
     */
    public function getSocialAuth($provider = null)
    {
        if (! config("services.$provider")) {
            abort('404');
        } //just to handle providers that doesn't exist
        if ($provider == 'facebook') {
            return Socialite::with($provider)
                ->scopes(
                    [
                        /*'publish_actions',*/
                        'email'
                    ]
                )
                ->redirect();  // for FB needs custom scopes 'user_friends'
        } else {
            return Socialite::with($provider)
                ->redirect();
        }
    }

    /**
     * @param null $provider
     * @return string
     */
    public function getSocialAuthCallback($provider = null)
    {
        if ($user = Socialite::with($provider)
            ->user()) {
            // if this is vk
            if ($provider == 'vkontakte') {
                // get remote new foto of social vk
                $url = 'https://api.vk.com/method/photos.get?owner_id=' . $user->getId() . '&album_id=profile&v=5.37';
                $decoded = remote($url);

                if (isset($decoded->status) && $decoded->status == 'ERROR') {
                    return redirect('/');
                }
                $photo = array_pop($decoded->response->items);
                $photo = $photo->photo_604;

                // put social url
                $social_url = 'https://vk.com/' . $user->getNickname();

                // get friends
                /*$url = 'https://api.vk.com/method/friends.get?user_id='.$user->getId().'&fields=photo&order=name&v=5.37&access_token='.$user->token;
                $decoded = remote($url);

                if (isset($decoded->status) && $decoded->status == 'ERROR') {

                    return redirect('/');
                }
                $friends = $decoded->response->items;
                Session::put('user.vkFriends', $friends);*/
            } else {
                // else this is fb
                Session::put('user.facebook', $user->token);
                $photo = $user->getAvatar();
                $social_url = 'https://www.facebook.com/' . $user->getId();
            }
            // get email
            if ($user->getEmail()) {
                $email = $user->getEmail();
            } else {
                $email = $user->getId();
            }

            // search for created earlier user
            $user_site = User::where('social_id', '=', $user->getId())
                ->orWhere('email', '=', $email)
                ->first();

            $checkName = explode(' ', $user->getName());
            $first_name = count($checkName) == 2 ? $checkName[0] : $user->getName();
            $last_name = count($checkName) == 2 ? $checkName[1] : '';

            if (! $user_site) {
                $user_site = new User();
                $user_site->social_id = $user->getId();
                $user_site->social_url = $social_url;
                $user_site->name = $first_name;
                $user_site->last_name = $last_name;
                $user_site->email = $email;
                $user_site->password = bcrypt($user->getId());
                $user_site->image = $photo;
                $user_site->isActive = true;
            } else {
                $user_site->social_url = $social_url;
                $user_site->name = $first_name;
                $user_site->last_name = $last_name;
                $user_site->image = $photo;
            }
            $user_site->save();
            $user_site->touch();

            // and login user
            Auth::loginUsingId($user_site->id);

            self::checkCookie();

            return redirect()->intended($this->redirectTo);
        } else {
            return redirect('/');
        }
    }


    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        $this->auth->logout();
        //Forget session for social
        if (Session::has('user.vkFriends')) {
            Session::forget('user.vkFriends');
        }
        if (Session::has('user.facebook')) {
            Session::forget('user.facebook');
        }

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /** check if user has not finished cart by its cookie
     * if yes and there are no current cart in session put to session cart data
     * @return bool
     */
    private static function checkCookie()
    {
        if (Cookie::has('cart_uid') && ! Session::has('cart_id')) {
            $cart_uid = Cookie::get('cart_uid');

            $cart = Cart::where('uid', '=', $cart_uid)
                ->where('deletion_mark', '<>', true)
                ->where('posted', '<>', true)
                ->first();

            if ($cart) {
                $products = CartProduct::where('cart_id', '=', $cart->id)
                    ->with([
                        'product' => function ($query) {
                            $query->with([
                                'image' => function ($query) {
                                    $query->orderBy('id', 'asc');
                                }
                            ]);
                        }
                    ])
                    ->whereHas('product', function ($query) {
                        $query->active();
                    })
                    ->get();

                if ($products) {
                    Session::put('cart_id', $cart->id);

                    foreach ($products as $product) {
                        Session::put(
                            'cart_products.' . $product->product_id,
                            [
                                'cart_id'       => $cart->id,
                                'product_id'    => $product->product_id,
                                'name_ru'       => $product->product->name_ru,
                                'name_en'       => $product->product->name_en,
                                'quantity'      => $product->quantity,
                                'price'         => $product->product->price,
                                'upi_id'        => $product->product->upi_id,
                                'image'         => $product->product->image->sortBy('sort')
                                    ->first()->image_url,
                                'delivery_type' => $product->product->delivery_type
                            ]
                        );
                    }
                }
            }
        }
    }

    public function getSocialDeauthCallback($provider = null)
    {
        return null;
    }

    public function getSocialDeleteCallback($provider = null)
    {
        return null;
    }
}
