@extends('layouts.app')

@push('styles')
<style type="text/css">
    @import url('https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap');

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .refresh {
        animation: rotate 3s linear infinite;
    }

    .container1 {
        position: relative;
        width: 400px;
        height: 400px;
        margin: 0 auto;
        margin-bottom: 10px;
        margin-top: 10px;
    }

    .container1 .spinBtn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: #fff;
        border-radius: 50%;
        z-index: 10;
        display: flex;
        justify-content: center;
        align-items: center;
        text-transform: uppercase;
        font-weight: 600;
        color: #333;
        letter-spacing: 0.1rem;
        border: 4px solid rgba(0, 0, 0, 0.75);
        cursor: pointer;
        user-select: none;
    }

    .container1 .spinBtn::before {
        content: '';
        position: absolute;
        top: -28px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 30px;
        background: #fff;
        clip-path: polygon(50% 0%, 15% 100%, 85% 100%);
    }

    .container1 .wheel {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #333;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 0 0 5px #333, 0 0 0 15px #fff, 0 0 0 18px #111;
    }
    .container1 .wheel .number {
        position: absolute;
        width: 50%;
        height: 50%;
        background: var(--clr);
        transform-origin: bottom right;
        transform: rotate(calc(30deg * var(--i)));
        clip-path: polygon(0 0, 40% 0, 100% 100%, 0 40%);
        display: flex;
        justify-content: center;
        align-items: center;
        user-select: none;
        cursor: pointer;
    }

    .container1 .wheel .number span {
        position: relative;
        transform: rotate(45deg);
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        text-shadow: 3px 5px 2px rgba(0, 0, 0, 0.15);
    }

    

</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Game</div>

                <div class="card-body">
                    <div class="text-center">
                        <div class="container1 ">
                            <div class="spinBtn">Win</div>
                            <div id="circle"class="wheel refresh">
                                <div class="number" style="--i:1;--clr:#db7093"><span>1</span></div>
                                <div class="number" style="--i:2;--clr:#20b2aa"><span>2</span></div>
                                <div class="number" style="--i:3;--clr:#d63e92"><span>3</span></div>
                                <div class="number" style="--i:4;--clr:#daa520"><span>4</span></div>
                                <div class="number" style="--i:5;--clr:#ff340f"><span>5</span></div>
                                <div class="number" style="--i:6;--clr:#ff7f50"><span>6</span></div>
                                <div class="number" style="--i:7;--clr:#3cb371"><span>7</span></div>
                                <div class="number" style="--i:8;--clr:#4169e1"><span>8</span></div>
                                <div class="number" style="--i:9;--clr:#4169e1"><span>9</span></div>
                                <div class="number" style="--i:10;--clr:#4169e1"><span>10</span></div>
                                <div class="number" style="--i:11;--clr:#4169e1"><span>11</span></div>
                                <div class="number" style="--i:12;--clr:#4169e1"><span>12</span></div>
                            </div>
                        </div>
                        <p id="winner" class="display-1 d-none text-primary"></p>
                    </div>
                    <hr>
                    <div class="text-center">
                        <label class="font-weight-bold h5">Your Bet</label>
                        <select id="bet" class="custom-select col-auto">
                            <option selected>Set you bet</option>
                            @foreach(range(1, 12) as $number)
                                <option>{{ $number }}</option>
                            @endforeach
                        </select>

                        <hr>
                        <p class="font-weight-bold h5">Remaining Time</p>
                        <p id="timer" class="h5 text-danger">Waiting to start</p>
                        <hr>
                        <p id="result" class="h1"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    const circleElement = document.getElementById('circle');
    const timerElement = document.getElementById('timer');
    const winnerElement = document.getElementById('winner');
    const betElement = document.getElementById('bet');
    const resultElement = document.getElementById('result');

    function disableBetSelection() {
        const remainingTime = parseInt(timerElement.innerText);
        if (remainingTime <= 5) {
            betElement.disabled = true;
            betElement.options[0].innerText = 'Too late';
        } else {
            betElement.disabled = false;
            betElement.options[0].innerText = 'Set you bet';
        }
    }



    Echo.channel('game').listen('RemainingTime', (e) => {
        timerElement.innerText = e.time;
        disableBetSelection();
        document.getElementById('circle');
        circleElement.classList.add('refresh');
        winnerElement.classList.add('d-none');
        resultElement.innerText = '';
        resultElement.classList.remove('text-success');
        resultElement.classList.remove('text-danger');

    })
        .listen('WinnerNumber', (e) => {
            let winner = e.number;
            winnerElement.innerText = winner;
            winnerElement.classList.remove('d-none');
            let bet = betElement[betElement.selectedIndex].innerText;
            if (bet == winner) {
                resultElement.innerText = 'You Win'
                resultElement.classList.add('text-success');
            } else {
                resultElement.innerText = 'You Lose'
                resultElement.classList.add('text-danger');
            }
        })


</script>
@endpush
