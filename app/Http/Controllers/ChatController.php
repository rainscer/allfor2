<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Session;
use Auth;

class ChatController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$chats = Chat::latest('updated_at')
			->with(['messages' => function($query){
				$query->with('participant');
			}])
			->with('participants')
			->get();

		return view('admin.chat.index',compact('chats'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getChats()
	{
		$chats = Chat::latest('updated_at')
			->with(['messages' => function($query){
				$query->with('participant');
			}])
			->with('participants')
			->get();

		$messageCount = ChatMessage::where('read',false)
			->whereHas('participant', function ($query) {
				$query->whereNotNull('user_session_id');
			})
			->count();

		$html = view('admin.chat._table',compact('chats'))->render();

		return response()->json([
			'result' 		=> 'OK',
			'chats' 		=> $html,
			'countMessages' => $messageCount
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$chat = new Chat();
		$chat->save();

		$participant_user = new ChatParticipant();
		$participant_user->user_session_id = Session::getId();
		$participant_user->chat_id = $chat->id;
		$participant_user->save();


		ChatParticipant::create([
			'support' 			=> true,
			'chat_id'			=> $chat->id
		]);

		ChatMessage::create([
			'chat_id'			=> $chat->id,
			'body'				=> Input::get('body'),
			'participant_id'	=> $participant_user->id
		]);

		return redirect('chat/' . $chat->id);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function createNewMessage($id)
	{
		$message = new ChatMessage();
		$message->chat_id = $id;
		$message->body = Input::get('message');

		$chat = Chat::find($id);
		$participants = $chat->participants()->get();

		$session_id = Session::getId();

		$participant_user = $participants->first(function($key,$value) use ($session_id){
			return $value->user_session_id == $session_id;
		});

		if($participant_user){
			$message->participant_id = $participant_user->id;
		}else{
			$participant_user = $participants->first(function($key,$value){
				return $value->support == true;
			});

			$message->participant_id = $participant_user->id;
		}
		$message->save();

		$messages = [];
		$messages[] = $message;

		$html = view('admin.chat.newMessages', compact(
			'messages'
		))->render();

		return response()->json([
			'result' => 'OK',
			'messages' => $html
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Auth::check() && Auth::user()->name == 'support') {
			$chat = Chat::where('id', $id)
				->with(['messages' => function ($query) {
					$query->with('participant');
				}])
				->first();

			if($chat) {
				$chat->messages()
					->whereHas('participant', function ($query) {
						$query->whereNotNull('user_session_id');
					})
					->where('read', false)
					->update([
						'read' => true
					]);
			}
		}else{

			$session_id = Session::getId();

			$chat = Chat::where('id', $id)
				->with(['messages' => function ($query) {
					$query->with('participant');
				}])
				->whereHas('participants',function($query) use($session_id){
					$query->where('user_session_id', $session_id);
				})
				->first();

			if($chat) {
				$chat->messages()
					->with('participant')
					->whereHas('participant', function ($query) use ($session_id) {
						$query->whereNull('user_session_id');
					})
					->where('read', false)
					->update([
						'read' => true
					]);
			}
		}

		if($chat) {
			return view('admin.chat.show', compact('chat'));
		}

		return redirect('/');
	}

	/**
	 * Shows a message thread.
	 *
	 * @param $id
	 * @return mixed
	 */
	public function getNewMessages($id)
	{
		$typing = Input::get('typing');

		$chat = Chat::find($id);
		$participants = $chat->participants()->get();

		$session_id = Session::getId();

		$participant_user = $participants->first(function ($key, $value) use ($session_id) {
			return $value->user_session_id == $session_id;
		});

		if (!$participant_user) {

			$userName = 'Пользователь';

			$participant_user = $participants->first(function ($key, $value) {
				return $value->support == true;
			});
		}else{
			$userName = 'Администратор';
		}
		$participant_user->is_typing = $typing == 'false' ? false : true;
		$participant_user->save();

		$now = Carbon::now();
		$participant_second_status = $participants->first(function ($key, $value) use($participant_user){
			return $value->id != $participant_user->id;
		})->updated_at;

		$dif = $now->diffInSeconds($participant_second_status);

		$messages = $chat->messages()
			->with('participant')
			->whereHas('participant', function ($query) use ($participant_user) {
				$query->where('id', '<>', $participant_user->id);
			})
			->where('read', false)
			->get();


		if ($messages->count()) {

			$messages_id = $messages->lists('id');
			$chat->messages()->whereIn('id', $messages_id)->update([
				'read' => true
			]);

			$user_typing = $participants->first(function ($key, $value) use ($participant_user) {
				return $value->id != $participant_user->id;
			})->is_typing;

			$html = view('admin.chat.newMessages', compact(
				'messages'
			))->render();

			return response()->json([
				'result' => 'OK',
				'messages' => $html,
				'countMessages' => $messages->count(),
				'isTyping'	=> $user_typing,
				'userTyping' => $userName . ' пишет сообщение...',
				'online'	=> $dif
			]);
		}

		$user_typing = $participants->first(function ($key, $value) use ($participant_user) {
			return $value->id != $participant_user->id;
		})->is_typing;

		return response()->json([
			'result' => 'OK',
			'messages' => '',
			'countMessages' => 0,
			'isTyping'	=> $user_typing,
			'userTyping' => $userName . ' пишет сообщение...',
			'online'	=> $dif
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
