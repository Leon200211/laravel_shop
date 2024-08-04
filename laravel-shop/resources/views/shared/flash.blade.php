@if($message = flash()->get())
    <div class="{{ $message->class() }}">
        {{ $message->message() }}
    </div>
@endif
