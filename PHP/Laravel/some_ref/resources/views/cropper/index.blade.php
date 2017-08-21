<html>

<link rel="stylesheet" href="{{url('css/bulma.css')}}">
<link rel="stylesheet" href="{{url('css/bulma-docs.css')}}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<link rel="stylesheet" href="{{url('css/cropper/cropper.min.css')}}">
<link rel="stylesheet" href="{{url('css/cropper/main.css')}}">


<style>

.cropper_form {
	position: absolute;
	
	width: 50%;
	height: 50%;
	left: 25%;
	top:  15%;
	font-size: 46px;
}

.cropper_button {
	
  	font-size: 26px;
	text-align: center;
	text-decoration: none;
	
}

</style>




<body>

<div class="cropper_form">
	<form method="post" id="upload_form" action="{{url('/test/cropper/cropper_upload')}}" enctype="multipart/form-data">
		{{csrf_field()}}

		<div class="columns">
			<div class="column is-8">

			    <div class="field">
	                <label> Use this form to crop & upload photo.</label>
	            	<h2>- You must <span style="color: blue">login</span> mycare to be able to upload photos.</h2>
	            	
	            </div>

	            <div class="field">
	                <label> </label>
	                <p class="control">
	                    <a class="cropper_button button is-primary" onclick="event.preventDefault();onClickAdd(this)">Click to add a photo</a>
						<input type="hidden" id="upload_hidden" name="croppedImage" />
	                </p>
	            </div>
			
				<div class="field">
	               	<button class="cropper_button button is-primary" >{{__('mycare.submit')}}</button>
	            </div>

			</div>			
		</div>
	</form>
</div>

<!--Modal for uploading photo-->
<div class="modal fade" id="upload_modal" style="background-color: rgba(224, 224, 224, 0.5)">
    <button type="button" class="cropper_button button is-primary" data-dismiss="modal">Close</button>
	@include('cropper.cropper_modal')
</div>





<script>

function onClickAdd(){
	$('#upload_modal').modal();
}

</script>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{url('js/cropper/common.js')}}"></script>
<script src="{{url('js/cropper/cropper.min.js')}}"></script>
<script src="{{url('js/cropper/main.js')}}"></script>


</body>
</html>
