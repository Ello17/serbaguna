@extends('layouts.app')

@section('title', 'Kalkulator')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Kalkulator</h4>
                </div>
                <div class="card-body">
                    <div class="calculator">
                        <div class="calculator-display mb-3">
                            <input type="text" class="form-control form-control-lg text-end" id="display" readonly>
                        </div>
                        <div class="calculator-buttons">
                            <div class="row mb-2">
                                <div class="col-3">
                                    <button class="btn btn-secondary w-100" onclick="clearDisplay()">C</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-secondary w-100" onclick="backspace()">←</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-secondary w-100" onclick="appendToDisplay('%')">%</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-warning w-100" onclick="appendToDisplay('/')">/</button>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-3">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('7')">7</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('8')">8</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('9')">9</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-warning w-100" onclick="appendToDisplay('*')">×</button>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-3">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('4')">4</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('5')">5</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('6')">6</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-warning w-100" onclick="appendToDisplay('-')">-</button>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-3">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('1')">1</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('2')">2</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('3')">3</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-warning w-100" onclick="appendToDisplay('+')">+</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('0')">0</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-light w-100" onclick="appendToDisplay('.')">.</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-warning w-100" onclick="calculate()">=</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function appendToDisplay(value) {
        document.getElementById('display').value += value;
    }

    function clearDisplay() {
        document.getElementById('display').value = '';
    }

    function backspace() {
        const display = document.getElementById('display');
        display.value = display.value.slice(0, -1);
    }

    function calculate() {
        const expression = document.getElementById('display').value;
        if (!expression) return;

        fetch("{{ route('calculator.calculate') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ expression: expression })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                document.getElementById('display').value = data.result;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghitung');
        });
    }

    // Allow keyboard input
    document.addEventListener('DOMContentLoaded', function() {
        const display = document.getElementById('display');
        display.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                calculate();
            }
        });
    });
</script>
@endpush
