<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }
        </style>
    </head>
    <body>
        <div class="flex-center full-height">
            <div>
                <form action="/subscription" method="post">
                    @csrf
                    <b>New Subscription</b>
                    <br>
                    <br>

                    <input type="name" name="first_name">
                    <label>First Name</label>
                    <br>

                    <input type="name" name="second_name">
                    <label>Second Name</label>
                    <br>

                    <input type="name" name="email">
                    <label>Email</label>
                    <br>

                    <input type="name" name="domain_name">
                    <label>Domain</label>
                    <br>

                    <input type="name" name="external_subscription_id">
                    <label>Subscription ID</label>
                    <br>

                    <input type="name" name="external_user_id">
                    <label>User ID</label>
                    <br>

                    <input type="name" name="product_id">
                    <label>Product ID</label>
                    <br>

                    <input type="name" name="state">
                    <label>State</label>
                    <br>

                    <input type="name" name="provision_state">
                    <label>Provision State</label>
                    <br>
                    <br>

                    <input type="submit" value="Create">
                </form>
                <br>
                <table border>
                    <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Domain</th>
                        <th>State</th>
                        <th>Provision State</th>
                        <th>External User ID</th>
                        <th>External Subscription ID</th>
                        <th>Delete</th>
                    </thead>
                    @foreach($subscriptions as $subscription)
                        <tbody>
                            <td>{{ $subscription->id }}</td>
                            <td>{{ $subscription->first_name }} {{ $subscription->last_name }}</td>
                            <td>{{ $subscription->email }}</td>
                            <td>{{ $subscription->domains[0]->domain_name }}</td>
                            <td>{{ $subscription->state }}</td>
                            <td>{{ $subscription->provision_state }}</td>
                            <td>{{ $subscription->external_user_id }}</td>
                            <td>{{ $subscription->external_subscription_id }}</td>
                            <td>
                                <form action="/subscription/{{ $subscription->id }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" value="Delete">
                                </form>
                            </td>
                        </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </body>
</html>
