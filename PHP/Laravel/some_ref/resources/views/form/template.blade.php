@extends('layouts.app')

@section('content')


    <div class="container container-top">

        <div class="block">
            <a class="button is-outlined is-primary" href="{{url('/form/listing')}}">{{trans_choice('mycare.form',2)}}</a>
            <a class="button is-outlined is-primary" href="{{url('/form/edit/'.$form->_id)}}">{{trans_choice('mycare.edit_form_header',0)}}</a>
        </div>

        <div class="columns">
            <div class="column is-9">
                <form method="post" action="{{url('/form/validateTemplate')}}">

                    {{ csrf_field() }}
                    
                    <div class="box">
                        <h1 class="title">{{$form->FormID}} - {{$form->FormName}}  <button class="button is-primary">{{__('mycare.save')}}</button></h1>
                        <h2 class="subtitle">{{__('mycare.template')}}</h2>
                        <div class="field">
                            <p class="control">
                                <textarea  class="textarea" style="height:auto" name="template_content">@if($content==''){{$form->template}}@else{{$content}}@endif</textarea>
                            </p>
                        </div>

                        <div class="field is-grouped">
                            <p class="control">
                                <button class="button is-primary">{{__('mycare.save')}}</button>
                            </p>
                        </div>
                    </div>

                    <input type="hidden" name="formId" value="{{$form->_id}}"/>

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                </form>
            </div>

            <div class="column is-3">
                <div class="box">
                    <table class="table is-striped">
                        <tr>
                            <td>#</td>
                            <td>Question numbering</td>
                        </tr>
                        <tr>
                            <td>$</td>
                            <td>Code for the question; must start with a letter</td>
                        </tr>
                        <tr>
                            <td>?</td>
                            <td>Text for the question</td>
                        </tr>
                        {{-- <tr>
                            <td>~</td>
                            <td>Additional information for user</td>
                        </tr> --}}
                        <tr>
                            <td>=</td>
                            <td>Begin and end of a block of message<br/>
<pre>
=
This is a message
* this is bullet point 1
* this is bullet point 2
> this is order point 1
> this is order point 2
=
</pre>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td>{}</td>
                            <td>Additional attributes to present the question</td>
                        </tr> --}}
                        <tr>
                            <td>@</td>
                            <td>Type of question; text, memo, number, date, time, checkbox, radio, dropdown</td>
                        </tr>
                        <tr>
                            <td>(000)</td>
                            <td>Code for the checkbox, radio, dropdown</td>
                        </tr>
                        {{-- <tr>
                            <td>+</td>
                            <td>Create a task for a follow up assessment / form</td>
                        </tr> --}}
                        <tr>
                            <td>^^</td>
                            <td>Prefix to start a question</td>
                        </tr>
                        <tr>
                            <td>&gt;</td>
                            <td>Link to care plan<br/>
                                e.g <strong>obs=Mobility &amp; Transfer</strong> <br/>
                                obs =&gt; Observation <br/>
                                intv =&gt; Intervention <br/>
                                goal =&gt; Goal <br/>
                                &quot;Mobility &amp; Transfer&quot; =&gt; domain
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>









    </div>


@endsection
