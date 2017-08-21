<html>
<head>
<title>Care Plan</title>
</head>
<body>
    <div class="container">

        <div class="columns">
            <div class="column is-8">
                @include('template.resident_header', ['resident' => $resident])
            </div>
           
        </div>

        @foreach($careplanView->Domains() as $domain)

        <div class="columns">
            <p class="title">{{$domain}}</p>
        </div>
        <div class="columns">
                <div class="column is-4">
                    <div class="panel">
                        <p class="panel-heading">
                            {{trans_choice('mycare.goal',2)}}
                        </p>
                        <div class="content">
                        <ul>
                        @foreach($careplanView->goals as $obs)
                            @if($obs['domain']==$domain)
                                <li >{{$obs['goal']}}
                                </li>
                            @endif
                        @endforeach
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="panel">
                        <p class="panel-heading">
                            {{trans_choice('mycare.observation',2)}}
                        </p>
                        <div class="content">
                            <ul>
                                @foreach($careplanView->observations as $obs)
                                    @if($obs['domain']==$domain)
                                        <li>{{$obs['text']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="column is-4">
                    <div class="panel">
                        <p class="panel-heading">
                            {{trans_choice('mycare.intervention',2)}}
                        </p>
                        <div class="content">
                            <ul>
                                @foreach($careplanView->interventions as $obs)
                                    @if($obs['domain']==$domain)
                                        <li>{{$obs['text']}}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>

        </div>
        @endforeach

    </div>
</body>
</html>