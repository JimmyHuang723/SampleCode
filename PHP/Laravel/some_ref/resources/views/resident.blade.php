@extends('layouts.app')

@section('title', 'Residents')

@section('sidebar')
    @parent
@endsection

@section('content')


    <div class="container">

        <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored"
                v-on:click="window.location='/home'">
            <i class="material-icons">arrow_back</i>
        </button>



        <ul class="demo-list-three mdl-list">
            @foreach($residents as $r)
            <li class="mdl-list__item mdl-list__item--three-line" v-on:click="window.location='/resident/view/{{$r->_id}}'">
                <span class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-avatar">person</i>
                    <span>{{$r->NameFirst}} {{$r->NameLast}}</span>
                    <span class="mdl-list__item-text-body">

                        Location: {{$r->LocationNameLong}}
                        Room {{$r->Room}}
                    </span>
                </span>
                <span class="mdl-list__item-secondary-content">
                    <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">star</i></a>
                </span>
            </li>
            @endforeach

        </ul>


        <a href="resident?currpage={{$currpage-1}}&limit={{$limit}}"><i class="fa fa-chevron-left"></i></a>
        <a href="resident?currpage={{$currpage+1}}&limit={{$limit}}"><i class="fa fa-chevron-right"></i></a>

    </div>




@endsection