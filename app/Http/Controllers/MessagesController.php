<?php namespace App\Http\Controllers;

use App\Models\Mailing;
use App\Models\Message;
use App\Models\Participant;
use App\Models\Thread;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Activity;


class MessagesController extends Controller {

	/**
	 * Show all of the message threads to the user.
	 *
	 * @return mixed
	 */
	public function index()
	{
		$currentUserId = Auth::user()->id;
		$user = User::getUserWithData($currentUserId);
		$title = trans('user.threads');
		// All threads, ignore deleted/archived participants
		if(Auth::user()->active && Auth::user()->name == 'support'){
			$threads = Thread::getAllLatest()->paginate(10);
			$support = true;
			$users = User::lists('name', 'id');
		}else{
			$threads = Thread::forUser($currentUserId)->latest('updated_at')->paginate(10);
			$support = false;
		}
		// All threads that user is participating in, with new messages
		//$threads = Thread::forUserWithNewMessages($currentUserId)->latest('updated_at')->get();

		return view('messenger.index', compact(
			'threads',
			'currentUserId',
			'user',
			'users',
			'support',
			'title'
		));
	}
	/**
	 * Shows a message thread.
	 *
	 * @param $id
	 * @return mixed
	 */
	public function show($id)
	{
		try {
			$thread = Thread::findOrFail($id);
		} catch (ModelNotFoundException $e) {
			Session::flash('error_message', trans('user.errorThreads'));
			return redirect('messages');
		}
		if(!$thread->hasParticipant(Auth::user()->id) && !Auth::user()->active){
			Session::flash('error_message', trans('user.errorThreads'));
			return redirect('messages');
		}

		$user = User::getUserWithData(Auth::user()->id);
		$title = trans('user.chat') . ' ' .$thread->subject;

		if(Auth::user()->name == 'support'){
			$support = true;
		}else {
			$support = false;
			$support_user = User::firstOrCreate(['name' => 'support']);
			$activities = Activity::users()->lists('user_id');
			$support_online = in_array($support_user->id, $activities);
		}

		$userId = Auth::user()->id;
		$users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();
		$new_messages = collect($thread->userUnreadMessages($userId))->lists('id');
		$thread->markAsRead($userId);

		return view('messenger.show', compact(
			'thread',
			'users',
			'user',
			'title',
			'support',
			'support_online',
			'new_messages'
		));
	}

	/**
	 * @return \Illuminate\View\View
     */
	public function getNewThreads()
	{
		$currentUserId = Auth::user()->id;
		$user = User::getUserWithData($currentUserId);
		$title = trans('user.newThreads');
		// All threads, ignore deleted/archived participants
		if(Auth::user()->active && Auth::user()->name == 'support'){
			$support = true;
			$users = User::lists('name', 'id');
		}else{
			$support = false;
		}
		// All threads that user is participating in, with new messages
		$threads = Thread::forUserWithNewMessages($currentUserId)->latest('updated_at')->paginate(10);

		return view('messenger.index', compact(
			'threads',
			'currentUserId',
			'user',
			'users',
			'support',
			'title'
		));
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	public function getTrashedThreads()
	{
		$currentUserId = Auth::user()->id;
		$user = User::getUserWithData($currentUserId);
		$title = trans('user.newThreads');
		// All threads, ignore deleted/archived participants
		if(Auth::user()->active && Auth::user()->name == 'support'){
			$support = true;
			$users = User::lists('name', 'id');
			$threads = Thread::onlyTrashed()->paginate(10);

			return view('messenger.index', compact(
				'threads',
				'currentUserId',
				'user',
				'users',
				'support',
				'title'
			));
		}else{

			return redirect('messages');
		}

	}

	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	public function getThreadsBetween()
	{
		$currentUserId = Auth::user()->id;
		$user = User::getUserWithData($currentUserId);
		$title = trans('user.newThreads');
		// All threads, ignore deleted/archived participants
		if(Auth::user()->active && Auth::user()->name == 'support' &&
			Input::has('recipient') && Input::get('recipient') > 0){
			$support = true;
			$users = [];
			$users[] = Auth::user()->id;
			$users[] = Input::get('recipient');
			// All threads that user is participating in, with new messages
			$threads = Thread::between($users)->paginate(10);
			$users = User::lists('name', 'id');

			return view('messenger.index', compact(
				'threads',
				'currentUserId',
				'user',
				'users',
				'support',
				'title'
			));

		}else{
			return redirect('messages');
		}
	}

	/**
	 * Shows a message thread.
	 *
	 * @param $id
	 * @return mixed
	 */
	public function getNewMessages($id)
	{
		try {
			$thread = Thread::findOrFail($id);
		} catch (ModelNotFoundException $e) {

			return response()->json([
				'result' => 'ERROR'
			]);
		}
		if(!$thread->hasParticipant(Auth::user()->id) && !Auth::user()->active){

			return response()->json([
				'result' => 'ERROR'
			]);
		}

		$support = Auth::user()->name == 'support' ? true : false;
		$userId = Auth::user()->id;
		$new_messages = $thread->userUnreadMessages($userId);
		if(count($new_messages)){
			$thread->markAsRead($userId);

			$html = view('messenger.newMessages', compact(
				'thread',
				'support',
				'new_messages'
			))->render();

			return response()->json([
				'result' 			=> 'OK',
				'messages' 			=> $html,
				'countMessages' 	=> count($new_messages)
			]);
		}

		return response()->json([
			'result' => 'NO MESSAGES'
		]);
	}

	/**
	 * Adds a new message to a current thread.
	 *
	 * @param $id
	 * @return mixed
	 */
	public function updateAjax($id)
	{
		try {
			$thread = Thread::findOrFail($id);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'result' => 'ERROR'
			]);
		}
		//$thread->activateAllParticipants();
		// Message
		$message = Message::create(
			[
				'thread_id' => $thread->id,
				'user_id'   => Auth::id(),
				'body'      => Input::get('message'),
			]
		);
		// Add replier as a participant
		$participant = Participant::firstOrCreate(
			[
				'thread_id' => $thread->id,
				'user_id'   => Auth::user()->id,
			]
		);
		$participant->last_read = new Carbon;
		$participant->save();
		// Recipients

		$message = $message->load('user');
		if($message->user->name == 'support'){
			Mailing::sendMailToUserNewMessage(head($thread->participantsUserIdsWithoutUser($message->user->id)));
		}
		$new_messages = [];
		$new_messages[] = $message;

		$html = view('messenger.newMessages', compact(
			'new_messages'
		))->render();

		return response()->json([
			'result' => 'OK',
			'messages' => $html
		]);
	}


	/**
	 * Creates a new message thread.
	 *
	 * @return mixed
	 */
	public function create()
	{
		$user = User::getUserWithData(Auth::user()->id);
		$title = trans('user.title');

		$support = Auth::user()->name == 'support' ? true : false;
		$users = User::where('id', '!=', Auth::id())->get();
		return view('messenger.create', compact(
			'users',
			'user',
			'support',
			'title'
		));
	}
	/**
	 * Stores a new message thread.
	 *
	 * @return mixed
	 */
	public function store()
	{
		$input = Input::all();
		$validator = Validator::make(
			[
				'subject'   => $input['subject'],
				'message'   => $input['message']
			],
			[
				'subject'	=> 'required',
				'message' 	=> 'required'
			]);

		if ($validator->fails())
		{
			return redirect()->back()->withErrors($validator->errors());
		}elseif(Auth::user()->name == 'support' && (!Input::has('recipient') || $input['recipient'] <= 0)){
			return redirect()->back()->withErrors(['recipient' => 'Recipient required']);
		}
		$thread = Thread::create(
			[
				'subject' => $input['subject'],
			]
		);
		// Message
		Message::create(
			[
				'thread_id' => $thread->id,
				'user_id'   => Auth::user()->id,
				'body'      => $input['message'],
			]
		);
		// Sender
		Participant::create(
			[
				'thread_id' => $thread->id,
				'user_id'   => Auth::user()->id,
				'last_read' => new Carbon,
			]
		);
		// Recipients
		if (Auth::user()->name != 'support') {
			$users = User::firstOrCreate(['name' => 'support']);
			$recipients = [];
			$recipients[] = $users->id;
			$thread->addParticipants($recipients);
		}elseif(Input::has('recipient') && $input['recipient'] > 0){
			Mailing::sendMailToUserNewMessage(head($thread->participantsUserIdsWithoutUser($input['recipient'])));
			$recipients = [];
			$recipients[]= $input['recipient'];
			$thread->addParticipants($recipients);
		}
		return redirect('messages/' . $thread->id);
	}

	/**
	 * Adds a new message to a current thread.
	 *
	 * @param $id
	 * @return mixed
	 */
	public function update($id)
	{
		try {
			$thread = Thread::findOrFail($id);
		} catch (ModelNotFoundException $e) {
			Session::flash('error_message', trans('user.errorThreads'));
			return redirect('messages');
		}
		$thread->activateAllParticipants();
		// Message
		Message::create(
			[
				'thread_id' => $thread->id,
				'user_id'   => Auth::id(),
				'body'      => Input::get('message'),
			]
		);
		// Add replier as a participant
		$participant = Participant::firstOrCreate(
			[
				'thread_id' => $thread->id,
				'user_id'   => Auth::user()->id,
			]
		);
		$participant->last_read = new Carbon;
		$participant->save();
		// Recipients

		return redirect('messages/' . $id);
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function delete()
	{
		$input = Input::all();
		if(Input::has('delete_threads')){
			if(Input::has('archive')){
				Thread::whereIn('id', $input['delete_threads'])
				->delete();
			}elseif(Input::has('delete')){
				foreach($input['delete_threads'] as $item){
					$thread = Thread::where('id',$item)->withTrashed()->first();
					if($thread) {
						$thread->participants()
							->forceDelete();
						$thread->messages()
							->forceDelete();
					}
				}
				Thread::whereIn('id', $input['delete_threads'])
					->withTrashed()
					->forceDelete();
			}elseif(Input::has('restore')){
				Thread::withTrashed()
					->whereIn('id', $input['delete_threads'])
					->restore();
			}
		}

		return redirect()->back();
	}
}
