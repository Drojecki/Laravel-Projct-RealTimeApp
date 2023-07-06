@extends('layouts.app')

@push('styles')
<style type="text/css">
   
    #users > li{
        cursor: pointer;
    }

</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Chat</div>

                <div class="card-body">
                    <div class="row p-2">
                        <div class="col-10">
                            <div class="row">
                                    <div class="col-12 border rounded-lg p-3"style="border-radius:5px;">
                                        <ul id="messages"class="list-unstyled overflow-auto"style="height:45vh">
                                            
                                        </ul>
                                    </div>
                                    <form action="">
                                        <div class="row py-3">
                                            <div class="col-10">
                                                <input type="text" id="message" class="form-control">
                                            </div>
                                            <div class="col-2"><button id="send"type="submit"class="btn btn-primary">Send</button>
                                            </div>
                                        </div>
                                    </form>
                            </div>
                        </div>
                        <div class="col-2">
                            <p><strong>Online Now</strong></p>
                            <ul id="users"class="list-unstyled overflow-auto text-info"style="height:45vh;">
                                    
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
   const usersElement = document.getElementById('users');
   const messageElement = document.getElementById('message');
   const messagesElement = document.getElementById('messages');
   const sendElement = document.getElementById('send');
   

   Echo.join('chat')
        .here((users) => {
            users.forEach((user, index) => {
                let element = document.createElement('li');
                element.setAttribute('id', user.id);
                element.setAttribute('onclick', 'greetUser("' + user.id +'")');
                element.innerText = user.name;
                usersElement.appendChild(element);
            });
        })
        .joining((user) => {
            let element = document.createElement('li');
            element.setAttribute('id', user.id);
            element.setAttribute('onclick', 'greetUser("' + user.id +'")');
            element.innerText = user.name;
            usersElement.appendChild(element);
        })
        .leaving((user) => {
            const element = document.getElementById(user.id);
            element.parentNode.removeChild(element);
        })
        .listen('MessageSent', (e) => {
            let element = document.createElement('li');
            element.innerText = e.user.name + ': ' + e.message;
            messagesElement.appendChild(element);
        });
        </script>

        <script type="module">
            const usersElement = document.getElementById('users');
            const messageElement = document.getElementById('message');
            const messagesElement = document.getElementById('messages');
            const sendElement = document.getElementById('send');

            sendElement.addEventListener('click', (e) => {
                e.preventDefault();
                window.axios.post('/chat/message', {
                    message: messageElement.value,
                });
                messageElement.value = '';
            });
        </script>

        <script>
            function greetUser(id)
            {
                window.axios.post('/chat/greet/' + id);
            }

        </script>

        <script type="module">
            const usersElement = document.getElementById('users');
            const messageElement = document.getElementById('message');
            const messagesElement = document.getElementById('messages');
            const sendElement = document.getElementById('send');

            var authenticatedUserId = {{ auth()->id() }};
            Echo.private('chat.greet.' + authenticatedUserId).listen('GreetingSent', (e) => {
                let element = document.createElement('li');
                element.innerText = e.message;
                element.classList.add('text-success');
                element.classList.add('text-uppercase');
                messagesElement.appendChild(element);
        
            });
    
</script>


@endpush
