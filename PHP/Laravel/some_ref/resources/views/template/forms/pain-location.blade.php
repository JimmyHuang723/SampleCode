
@if(isset($view_mode))
<div class="box">
    <div class="columns">
        <div class="column is-4">
            <div class="field">
                <label>1. Date commenced</label>
                <p class="contorl">
                    <input class="input" id="datepicker" name="PLC01" value="{{array_get($data, 'PLC01')}}" style="width:150px" />
                </p>
            </div>
        </div>
        <div class="column is-4">
            <div class="field">
                <label>2. Time</label>
                <p class="contorl">
                    <input class="input" id="timepicker" name="PLC03" value="{{array_get($data, 'PLC03')}}" style="width:150px" />
                </p>
            </div>
        </div>
        <div class="column is-4">
            <div class="field">
                <label>3. Location of the pain</label>
                <p class="contorl">
                    <input class="input" type="text" id="PLC05" name="PLC05" value="{{array_get($data, 'PLC05')}}" style="width:150px" />
                </p>
            </div>
        </div>
    </div>
</div>
@else
<div class="box">
    <div class="columns">
        <div class="column is-4">
                <div class="field">
                <label>1. Date commenced</label>
                <p class="contorl">
                    <input class="input" id="datepicker" name="PLC01" value="{{array_get($data, 'PLC01')}}" style="width:150px" />
                </p>
            </div>
            <div class="field">
                <label>2. Time</label>
                <p class="contorl">
                    <input class="input" id="timepicker" name="PLC03" value="{{array_get($data, 'PLC03')}}" style="width:150px" />
                </p>
            </div>
            <div class="field">
                <label>3. Location of the pain</label>
                <p class="contorl">
                    <input class="input" type="text" id="PLC05" name="PLC05" value="{{array_get($data, 'PLC05')}}" style="width:150px" />
                </p>
            </div>
            
        </div>
        <div class="column is-8">

            <img src="{{asset('/images/body-map.png')}}" usemap="#body-map" id="body-map"/>


            @if(array_get($data, 'PLC05')=='Head')
            <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 102px;
               top: 5px;
               z-index: 1000;"></div>
            @endif


            @if(array_get($data, 'PLC05')=='Chest')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 102px;
               top: 60px;
               z-index: 1000;"></div>
            @endif


            @if(array_get($data, 'PLC05')=='Head (back)')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 268px;
               top: 5px;
               z-index: 1000;"></div>
            @endif


            @if(array_get($data, 'PLC05')=='Right arm')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 60px;
               top: 75px;
               z-index: 1000;"></div>
            @endif

            @if(array_get($data, 'PLC05')=='Left arm')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 145px;
               top: 78px;
               z-index: 1000;"></div>
            @endif

            @if(array_get($data, 'PLC05')=='Right palm')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 42px;
               top: 171px;
               z-index: 1000;"></div>
            @endif

            @if(array_get($data, 'PLC05')=='Abdomen')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 102px;
               top: 125px;
               z-index: 1000;"></div>
            @endif


            @if(array_get($data, 'PLC05')=='Left palm')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 162px;
               top: 171px;
               z-index: 1000;"></div>
            @endif


            @if(array_get($data, 'PLC05')=='Left hand')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 209px;
               top: 171px;
               z-index: 1000;"></div>
            @endif

            @if(array_get($data, 'PLC05')=='Lower back')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 269px;
               top: 131px;
               z-index: 1000;"></div>
            @endif


            @if(array_get($data, 'PLC05')=='Right hand')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 329px;
               top: 171px;
               z-index: 1000;"></div>
            @endif

        @if(array_get($data, 'PLC05')=='Left arm (back)')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 225px;
               top: 78px;
               z-index: 1000;"></div>
            @endif

            @if(array_get($data, 'PLC05')=='Upper back')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 269px;
               top: 55px;
               z-index: 1000;"></div>
            @endif

            @if(array_get($data, 'PLC05')=='Right arm (back)')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 315px;
               top: 73px;
               z-index: 1000;"></div>
            @endif

            @if(array_get($data, 'PLC05')=='Right leg')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 85px;
               top: 195px;
               z-index: 1000;"></div>
            @endif

            @if(array_get($data, 'PLC05')=='Left leg')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 120px;
               top: 195px;
               z-index: 1000;"></div>
        @endif

            @if(array_get($data, 'PLC05')=='Left leg (back)')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 250px;
               top: 195px;
               z-index: 1000;"></div>
        @endif



            @if(array_get($data, 'PLC05')=='Right leg (back)')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 290px;
               top: 195px;
               z-index: 1000;"></div>
        @endif


            @if(array_get($data, 'PLC05')=='Right foot')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 85px;
               top: 315px;
               z-index: 1000;"></div>
        @endif


            @if(array_get($data, 'PLC05')=='Left foot')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 124px;
               top: 310px;
               z-index: 1000;"></div>
        @endif



            @if(array_get($data, 'PLC05')=='Left foot (back)')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 249px;
               top: 311px;
               z-index: 1000;"></div>
        @endif


            @if(array_get($data, 'PLC05')=='Right foot (back)')
                <div style="
                border: solid 1px cadetblue; width:37px; height: 37px; border-radius: 25px;  float: left;
                position: relative; background: lightblue; opacity: .4;
               left: 289px;
               top: 311px;
               z-index: 1000;"></div>
        @endif
                    <!-- http://imagemap-generator.dariodomi.de/ -->
                    <map name="body-map" id="map">
            <area shape="circle" coords="23,192,19" alt="Right palm" href="#" onclick="setLocation(this)"/>
            <area shape="circle" coords="145,192,19" alt="Left palm" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="188,192,19" alt="Left hand" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="312,191,19" alt="Right hand" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="84,26,19" alt="Head" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="251,26,19" alt="Head (back)" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="250,69,19" alt="Upper back" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="84,79,19" alt="Chest" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="251,145,19" alt="Lower back" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="44,94,19" alt="Right arm" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="127,97,19" alt="Left arm" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="85,155,19" alt="Abdomen" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="208,96,19" alt="Left arm (back)" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="293,94,19" alt="Right arm (back)" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="65,212,19" alt="Right leg" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="105,210,19" alt="Left leg" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="230,216,19" alt="Left leg (back)" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="273,215,19" alt="Right leg (back)" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="64,329,19" alt="Right foot" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="105,328,19" alt="Left foot" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="269,328,19" alt="Right foot (back)" href="#"  onclick="setLocation(this)"/>
            <area shape="circle" coords="230,326,19" alt="Left foot (back)" href="#"  onclick="setLocation(this)"/>
                    </map>
        </div>
    </div>
    
</div>
@endif

@section('script')
    <script>
        function setLocation(loc){
            $('#PLC05').val(loc.alt);
        }
    </script>
    
@endsection