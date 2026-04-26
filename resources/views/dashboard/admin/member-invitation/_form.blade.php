{{ Form::bs4HorText('email', $invitation->email, request()->completed ? ['disabled' => true] : []) }}
