
<div class="box">
    <div class="media">
        <figure class="media-left">
            <p class="image is-64x64">
                <img id="resident_header_img" src="{{asset('storage/resident_photos/residentId_'.$resident->_id.'.jpg')}}" alt="" title="" />
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <h3>{{$resident->NameLast}}, {{$resident->NameFirst}}</h3>
                <ul>
                    <li>{{__('mycare.room')}}: {{$resident->Room}}</li>
                    <li>{{__('mycare.ccs')}}: {{$resident->CCS}}</li>
                </ul>

            </div>
        </div>
        @if(isset($editable) && $editable)
        <div class="media-right">
            <a href="{{url('resident/edit/'.$resident->_id)}}"
               class="is-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
        </div>
        @endif
    </div>
</div>
