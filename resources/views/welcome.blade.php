<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>No Longer Tracking Time</title>

    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <main class="container">
        <h1>no longer tracking time - <a href="https://youtu.be/CMSAELTTgrk" target="_blank" rel="noopener">Slowdive</a></h1>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Rate</th>
                    <th>Time</th>
                    <th>Owed</th>
                    {{-- <th>Received</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->client }}</td>
                    <td>&#36;{{ $invoice->rate }}</td>
                    <td><abbr title="{{ $invoice->startDateTime->toDateTimeString() }} &rarr; {{ $invoice->endDateTime->toDateTimeString() }}">
                        {{ $invoice->hours }} hours
                    </abbr></td>
                    <td>&#36;{{ $invoice->owed }}</td>
                    {{-- <td><form action=""><input type="checkbox" name="paid" id="paid"></form></td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="hoursForm">
            <fieldset>
                <legend>Add Hours</legend>
                <div>
                    <label for="client">Client:</label>
                    <input id="client" name="client" type="text" required>
            </div>
                <div>
                    <label for="rate">Rate:</label>
                    <input id="rate" name="rate" type="number" min="1" required>
                </div>
                {{-- <div>
                    <label for="hours">hours:</label>
                    <input id="hours" name="hours" type="number" min="1" required>
                </div> --}}

                <div>
                    <label for="startTime">Start/End Datetime:</label>
                    <input id="startDateTime" name="startDateTime" type="datetime-local" value="{{ date('Y-m-d\T00:00') }}" required>
                    &mdash;
                    <input id="endDateTime" name="endDateTime" type="datetime-local" value="{{ date('Y-m-d\T00:00') }}" required>
                </div>

                <button type="submit">Add</button>
                <button type="reset">Reset</button>
            </fieldset>
        </form>
    </main>

    <script>
        'use strict';

        const form = document.getElementById('hoursForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const client        = document.getElementById('client').value;
            const rate          = document.getElementById('rate').value;
            const startDateTime = document.getElementById('startDateTime').value;
            const endDateTime   = document.getElementById('endDateTime').value;
            const token         = document.querySelector('meta[name="csrf-token"]').content;

            const body = JSON.stringify({
                client: client,
                rate: rate,
                startDateTime: startDateTime,
                endDateTime: endDateTime
            });

            const request = new Request("{{ route('invoices.store') }}", {
                method: "POST",
                body: body,
                headers: new Headers({
                    'X-CSRF-TOKEN': token
                })
            });
            fetch(request)
            .then(function(res){ console.log(res) })
            .catch(function(res){ console.log(res) });
        });
    </script>
</body>
</html>
