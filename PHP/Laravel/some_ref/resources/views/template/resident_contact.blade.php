<div class="card" @if (isset($new)) id="new_contact" @endif 
     @if ( isset($contact['status']) and $contact['status']=='0')
     style="display: none"
     @endif
     >
    <header class="card-header">
        <p class="card-header-title"> 
            @if (isset($other))
                <label class="label has-text-left">{{__('mycare.title')}}</label>
                <input class="input" type="text" name="{{$input_name}}[title]" 
                @if (isset($contact['title']))
                value="{{$contact['title']}}"
                @else
                value="{{$title}}"
                @endif
                />
            @else
                {{$title}}
            @endif
        </p>
    </header>
    <div class="card-content">    
        <div class="columns">
            <div class="column is-6">

                <div class="field">
                    <label class="label has-text-left">{{__('mycare.first_name')}}</label>
                    <div class="control">
                        <input class="input" type="text" name="{{$input_name}}[first_name]"
                        @if (isset($contact['first_name']))
                        value="{{$contact['first_name']}}"
                        @endif
                        />
                    </div>
                </div>

                <div class="field">
                    <label class="label has-text-left">{{__('mycare.last_name')}}</label>
                    <div class="control">
                        <input class="input" type="text" name="{{$input_name}}[last_name]"
                        @if (isset($contact['last_name']))
                        value="{{$contact['last_name']}}"
                        @endif
                        />
                    </div>
                </div>

                <div class="field">
                    <label class="label has-text-left">{{__('mycare.address')}}</label>
                    <div class="control">
                        <input class="input" type="text" name="{{$input_name}}[address]" 
                        @if (isset($contact['address']))
                        value="{{$contact['address']}}"
                        @endif
                        />
                    </div>
                </div>

                <div class="field">
                    <label class="label has-text-left">{{__('mycare.suburb')}}</label>
                    <div class="control">
                        <input class="input" type="text" name="{{$input_name}}[suburb]" 
                        @if (isset($contact['suburb']))
                        value="{{$contact['suburb']}}"
                        @endif                        
                        />
                    </div>
                </div>

                <div class="field">
                    <label class="label has-text-left">{{__('mycare.city')}}</label>
                    <div class="control">
                        <input class="input" type="text" name="{{$input_name}}[city]" 
                        @if (isset($contact['city']))
                        value="{{$contact['city']}}"
                        @endif  
                        />
                    </div>
                </div>

            </div>
            <div class="column is-6">
            
                <div class="field">
                    <label class="label has-text-left">{{__('mycare.postcode')}}</label>
                    <div class="control">
                        <input class="input" type="text" name="{{$input_name}}[postcode]" 
                        @if (isset($contact['postcode']))
                        value="{{$contact['postcode']}}"
                        @endif  
                        />
                    </div>
                </div>

                <div class="field">
                    <label class="label has-text-left">{{__('mycare.contact_mobile')}}</label>
                    <div class="control">
                        <input class="input" type="text" name="{{$input_name}}[mobile]" 
                        @if (isset($contact['mobile']))
                        value="{{$contact['mobile']}}"
                        @endif  
                        />
                    </div>
                </div>

                <div class="field">
                    <label class="label has-text-left">{{__('mycare.contact_phone')}}</label>
                    <div class="control">
                        <input class="input" type="text" name="{{$input_name}}[phone]" 
                        @if (isset($contact['phone']))
                        value="{{$contact['phone']}}"
                        @endif  
                        />
                    </div>
                </div>

                <div class="field">
                    <label class="label has-text-left">{{__('mycare.contact_email')}}</label>
                    <div class="control">
                        <input class="input" type="text" name="{{$input_name}}[email]" 
                        @if (isset($contact['email']))
                        value="{{$contact['email']}}"
                        @endif  
                        />
                    </div>
                </div>

                <div class="field">
                    <label class="label has-text-left">{{__('mycare.relationship')}}</label>
                    <div class="control">
                        <input class="input" type="text" name="{{$input_name}}[relationship]" 
                        @if (isset($contact['relationship']))
                        value="{{$contact['relationship']}}"
                        @endif                          
                        />
                    </div>
                </div>

            </div>

            <!-- hidden field : archive status-->
            <input type="hidden" name="{{$input_name}}[status]" 
            @if (isset($contact['status']))
            value="{{$contact['status']}}"
            @else
            value="1"
            @endif 
            />
            

        </div> <!--<div class="columns">-->
    </div> <!--<div class="card-content">-->    
    <footer class="card-footer">
        @if (!isset($new))
        <a class="card-footer-item contact-edit-btn">Edit</a>
        <a class="card-footer-item" input_name="{{$input_name}}" onclick="event.preventDefault();onClickArchive(this)">{{__('mycare.archive')}}</a>
        @endif
    </footer>        
</div> <!--<div class="card">-->     