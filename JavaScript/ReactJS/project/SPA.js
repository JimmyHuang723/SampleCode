

/////////////////////////////////////////////////////////////
//////////////////////LoadInnerHtml ////////////////////////////////
/////////////////////////////////////////////////////////////
// https://facebook.github.io/react/docs/dom-elements.html
// http://stackoverflow.com/questions/40108843/react-how-to-load-and-render-external-html-file
var LoadInnerHtml = React.createClass({

  propTypes: {
    html: React.PropTypes.object.isRequired,
    afterRender: React.PropTypes.func.isRequired,
  },

  getInitialState: function(){
    return {  };
  },

  componentDidMount: function() {
    this.props.afterRender();
  },

  createMarkup: function() { 

    // https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest/Synchronous_and_Asynchronous_Requests
    //console.log("createMarkup.....");
    var allHTML = "default";
    var rawFile = new XMLHttpRequest();
    rawFile.open("GET", this.props.html.file_path, false); // true:async, false:sync
    rawFile.onload = function ()
    {
        //console.log("XMLHttpRequest onreadystatechange...");
        if(rawFile.readyState === 4)
        {
            if(rawFile.status === 200 || rawFile.status == 0)
            {
                allHTML = rawFile.responseText;
                //console.log(allHTML);
            }else{
                console.log("Error : componentLoadInnerHtml fail to read :" + this.props.html);
            }
        }
    };

    rawFile.onerror = function (e) {
      console.error(rawFile.statusText);
    };

    rawFile.send(null); // actually initiates the request.  The callback routine is called whenever the state of the request changes.

    return {__html: allHTML};
  },

  render: function() {
    return (
      <div dangerouslySetInnerHTML={this.createMarkup()} ></div>
    );
  }
});





/////////////////////////////////////////////////////////////
//////////////////////search ////////////////////////////////
/////////////////////////////////////////////////////////////


// Let's create a "real-time search" component

var SearchExample = React.createClass({

    getInitialState: function(){
        return { searchString: '' };
    },

    handleChange: function(e){

        // If you comment out this line, the text box will not change its value.
        // This is because in React, an input cannot change independently of the value
        // that was assigned to it. In our case this is this.state.searchString.

        this.setState({searchString:e.target.value});
    },

    render: function() {

        var libraries = this.props.items,
            searchString = this.state.searchString.trim().toLowerCase();


        if(searchString.length > 0){

            // We are searching. Filter the results.

            libraries = libraries.filter(function(l){
                return l.name.toLowerCase().match( searchString );
            });

        }

        return <div>
                    <input type="text" value={this.state.searchString} onChange={this.handleChange} placeholder="Type here" />

                    <ul> 

                        { libraries.map(function(l){
                            return <li>{l.name} <a href={l.url}>{l.url}</a></li>
                        }) }

                    </ul>

                </div>;

    }
});

                                                                                                                                                             
var libraries = [

    { name: 'Backbone.js', url: 'http://documentcloud.github.io/backbone/'},
    { name: 'AngularJS', url: 'https://angularjs.org/'},
    { name: 'jQuery', url: 'http://jquery.com/'},
    { name: 'Prototype', url: 'http://www.prototypejs.org/'},
    { name: 'React', url: 'http://facebook.github.io/react/'},
    { name: 'Ember', url: 'http://emberjs.com/'},
    { name: 'Knockout.js', url: 'http://knockoutjs.com/'},
    { name: 'Dojo', url: 'http://dojotoolkit.org/'},
    { name: 'Mootools', url: 'http://mootools.net/'},
    { name: 'Underscore', url: 'http://documentcloud.github.io/underscore/'},
    { name: 'Lodash', url: 'http://lodash.com/'},
    { name: 'Moment', url: 'http://momentjs.com/'},
    { name: 'Express', url: 'http://expressjs.com/'},
    { name: 'Koa', url: 'http://koajs.com/'},

];




  



/////////////////////////////////////////////////////////////
//////////////////////dom email//////////////////////////////
/////////////////////////////////////////////////////////////



var ContactForm = React.createClass({
  propTypes: {
    value: React.PropTypes.object.isRequired,
    onChange: React.PropTypes.func.isRequired,
    onSubmit: React.PropTypes.func.isRequired,
  },

  onEmailInput: function(e) {
    console.log("onEmailInput : ");
    console.log(this.props.value);
    console.log(e);
    this.props.onChange(Object.assign({}, this.props.value, {email: e.target.value}));
  },

  onNameInput: function(e) {
    this.props.onChange(Object.assign({}, this.props.value, {name: e.target.value}));
  },

  onSubmit: function(e) {
    e.preventDefault();
    this.refs.email.focus();
    this.props.onSubmit();
  },
  
  componentDidUpdate: function(prevProps) {
    var value = this.props.value;
    var prevValue = prevProps.value;
    
    if (this.isMounted && value.errors && value.errors != prevValue.errors) {
      if (value.errors.email) {
        this.refs.email.focus();
      }
      else if (value.errors.name) {
        this.refs.name.focus();
      }
    }
  },

  render: function() {
    var errors = this.props.value.errors || {};

    return (
      <h1> {"Keep scrolling down...you will see more and more..."} </h1>
      /*
      React.createElement('form', {onSubmit: this.onSubmit, className: 'ContactForm', noValidate: true},
        React.createElement('input', {
          type: 'text',
          className: errors.email && 'ContactForm-error',
          placeholder: 'New Post',
          onInput: this.onEmailInput,
          value: this.props.value.email,
          ref: 'email',
          autoFocus: true,
        }),
        React.createElement('input', {
          type: 'text',
          className: errors.name && 'ContactForm-error',
          placeholder: 'Name',
          onInput: this.onNameInput,
          value: this.props.value.name,
          ref: 'name',
        }),
        React.createElement('button', {type: 'submit'}, "Add Contact")
      )
      */

    );
  },
});


var ContactItem = React.createClass({
  propTypes: {
    name: React.PropTypes.string.isRequired,
    email: React.PropTypes.string.isRequired,
  },

  render: function() {

    var imgSrc = "";
    if(this.props.name % 6 == 0){
      imgSrc = "http://i.stack.imgur.com/WCveg.jpg";
    }else if(this.props.name % 6 == 1){
      imgSrc = "http://www.jqueryscript.net/images/Simplest-Responsive-jQuery-Image-Lightbox-Plugin-simple-lightbox.jpg";
    }else if(this.props.name % 6 == 2){
      imgSrc = "https://s3-us-west-1.amazonaws.com/powr/defaults/image-slider2.jpg";
    }else if(this.props.name % 6 == 3){
      imgSrc = "http://beebom.redkapmedia.netdna-cdn.com/wp-content/uploads/2016/01/Reverse-Image-Search-Engines-Apps-And-Its-Uses-2016.jpg";
    }else if(this.props.name % 6 == 4){
      imgSrc = "http://www.jqueryscript.net/images/jQuery-Plugin-For-Fullscreen-Image-Viewer-Chroma-Gallery.jpg";
    }else{
      imgSrc = "https://australianmuseum.net.au/uploads/images/34570/1108%20(1)_big.jpg";
    }


    return (
      React.createElement('li', {className: 'ContactItem'},
        React.createElement('h2', {className: 'ContactItem-email'}, this.props.email),
        React.createElement('span', {className: 'ContactItem-name'}, this.props.name),
        React.createElement('img', {src : imgSrc, width : "70%", height:"70%"})
      )
    );
  },
});


var ContactView = React.createClass({
  propTypes: {
    contacts: React.PropTypes.array.isRequired,
    newContact: React.PropTypes.object.isRequired,
    onNewContactChange: React.PropTypes.func.isRequired,
    onNewContactSubmit: React.PropTypes.func.isRequired,
  },

  componentDidMount: function() {
    //console.log("addEventListener in ContactView");

    // Bind to scroll bar of current Div : 
    document.getElementById("middleDiv").addEventListener("scroll", this.handleScroll);
    // Bind to scroll bar of browser window : (mobile browser) 
    window.addEventListener("scroll", this.handleScrollWindow);
  },

  handleScrollWindow: function(event) {

      //console.log("Window : scrollllll!!!");

      const windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;      
      const body = document.body;
      const html = document.documentElement;
      const docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight,  html.scrollHeight, html.offsetHeight);
      const windowBottom = windowHeight + window.pageYOffset;
      //console.log('windowHeight : '.concat(windowHeight));
      //console.log('pageYOffset : '.concat(window.pageYOffset));
      //console.log('docHeight : '.concat(docHeight));
      if (windowBottom >= (docHeight * 0.95 )) {
        console.log('Window : bottom reached');
        this.addContactItem();
      } else {
        console.log('Window : NOT at bottom');        
      }  
  },

  handleScroll: function(event) {

      console.log("scrollllll!!!");
      //console.log(event);
    
      //console.log("scrollTop" + $("#middleDiv").scrollTop());
      //console.log("innerHeight" + $("#middleDiv").innerHeight());
      //console.log("scrollHeight" + $("#middleDiv")[0].scrollHeight);
      if($("#middleDiv").scrollTop() + $("#middleDiv").innerHeight() 
              >= $("#middleDiv")[0].scrollHeight * 0.95) {
            console.log('Div : Bottom reached');

            // Add item automatically when on bottom...
            this.addContactItem();

      }else{
            console.log('Div : NOT at bottom');
      }


  },

  addContactItem: function(){
    counter_scroll_end ++;
    CONTACT_TEMPLATE_2.name = counter_scroll_end; 
    var number = "number ".concat(counter_scroll_end);
    CONTACT_TEMPLATE_2.email = number.concat("'s post");
    this.props.onNewContactChange(CONTACT_TEMPLATE_2);
    this.props.onNewContactSubmit();
  },

  render: function() {
    var contactItemElements = this.props.contacts
      .map(function(contact) { return React.createElement(ContactItem, contact); });

    return (
      React.createElement('div', {className: 'ContactView'},
        React.createElement('h1', {className: 'ContactView-title'}, "Keep scrolling down...you will see more and more..."),
        React.createElement('ul', {className: 'ContactView-list'}, contactItemElements),
        React.createElement(ContactForm, {
          ref: 'contactform',
          value: this.props.newContact,
          onChange: this.props.onNewContactChange,
          onSubmit: this.props.onNewContactSubmit,
        })
      )
    );
  },
});



/*
 * Constants
 */


var CONTACT_TEMPLATE = {name: "", email: "", description: "", errors: null};
var CONTACT_TEMPLATE_2 = {name: "xxx", email: "@gmail.com", description: "", errors: null};

var counter_scroll_end = 0;

/*
 * Model
 */


// The app's complete current state
var state = {};

// Make the given changes to the state and perform any required housekeeping
function setState(changes) {
  //console.log("setState : ");
  //console.log(changes);
  Object.assign(state, changes);
  
  
  ReactDOM.render(
    React.createElement(ContactView, 
      Object.assign({}, state, 
        {
          onNewContactChange: updateNewContact,
          onNewContactSubmit: submitNewContact,
        }
      )
    ),
    document.getElementById('divtest2')
  );

}


/*
 * Actions
 */


function updateNewContact(contact) {
  //console.log(contact);
  setState({ newContact: contact });
}


function submitNewContact() {
  var contact = Object.assign({}, state.newContact, {key: state.contacts.length + 1, errors: {}});

  if (!/.+@.+\..+/.test(contact.email)) {
    //contact.errors.email = ["Please enter your new contact's email"];
  }
  if (!contact.name) {
    contact.errors.name = ["Please enter your new contact's name"];
  }
  
  setState(
    Object.keys(contact.errors).length === 0
    ? {
        newContact: Object.assign({}, CONTACT_TEMPLATE),
        contacts: state.contacts.slice(0).concat(contact),
      }
    : { newContact: contact }
  );
}


/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
///////////////////order form///////////////////////////////
///////////////////////////////////////////////////////////


var Service = React.createClass({

    getInitialState: function(){
        return { active: false };
    },

    clickHandler: function (){

        var active = !this.state.active;

        this.setState({ active: active });
        
        // Notify the ServiceChooser, by calling its addTotal method
        this.props.addTotal( active ? this.props.price : -this.props.price );

    },

    render: function(){

        return  <p className={ this.state.active ? 'active' : '' } onClick={this.clickHandler}>
                    {this.props.name} <b>${this.props.price.toFixed(2)}</b>
                </p>;

    }

});



var ServiceChooser = React.createClass({

    getInitialState: function(){
        return { total: 0 };
    },

    addTotal: function( price ){
        this.setState( { total: this.state.total + price } );
    },

    render: function() {

        var self = this;

        var services = this.props.items.map(function(s){

            // Create a new Service component for each item in the items array.
            // Notice that I pass the self.addTotal function to the component.

            return <Service name={s.name} price={s.price} active={s.active} addTotal={self.addTotal} />;
        });

        return <div>
                    <h1>Click on any items...</h1>
                    
                    <div id="services">
                        {services}

                        <p id="total">Total <b>${this.state.total.toFixed(2)}</b></p>

                    </div>

                </div>;

    }
});






var services = [
    { name: 'Web Development', price: 300 },
    { name: 'Design', price: 400 },
    { name: 'Integration', price: 250 },
    { name: 'Training', price: 220 }
];









/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
//////////////////////Component for Router///////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////

var { Router,
      Route,
      IndexRoute,
      IndexLink,
      Link } = ReactRouter; 


var Home = React.createClass({
  render: function() {
      return (
        <div>
          <h2>HELLO</h2>
          <p>Cras facilisis urna ornare ex volutpat, et
          convallis erat elementum. Ut aliquam, ipsum vitae
          gravida suscipit, metus dui bibendum est, eget rhoncus nibh
          metus nec massa. Maecenas hendrerit laoreet augue
          nec molestie. Cum sociis natoque penatibus et magnis
          dis parturient montes, nascetur ridiculus mus.</p>
  
          <p>Duis a turpis sed lacus dapibus elementum sed eu lectus.</p>
        </div>
      );
    }
});


var Contact = React.createClass({
  render: function() {
      return (
        <div>
          <h2>GOT QUESTIONS?</h2>
          <p> ...
          </p>
        </div>
      );
    }
});
 
var Stuff = React.createClass({
  render: function() {
      return (
        <div>
          <h2>STUFF</h2>
          <p>Mauris sem velit, vehicula eget sodales vitae,
          rhoncus eget sapien:</p>
          <ol>
            <li>Nulla pulvinar diam</li>
            <li>Facilisis bibendum</li>
            <li>Vestibulum vulputate</li>
            <li>Eget erat</li>
            <li>Id porttitor</li>
          </ol>
        </div>
      );
    }
});


///
var componentAbout = React.createClass({

  afterRender: function() {
    //alert("afterRender componentAbout");
  }, 

  render: function() {
      var html_object = { file_path : "about.html"} ;
      return (
        <LoadInnerHtml  html= {html_object}  afterRender={this.afterRender}   />                 
      );
  }

});


///
var componentImageGallery = React.createClass({

  afterRender: function() {

    function openModal(){
      $("#middleDiv").css({ "overflow": "hidden",  }); // Hide scroll to fix bug : lapton screen scroll bar is on top of modal
      modal.style.display = "block";
      
    }

    function closeModal(){
      $("#middleDiv").css({ "overflow": "auto",  });
      modal.style.display = "none";
      
    }

    // Get the modal
    var modal = document.getElementById('myModal');

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var modalImg = document.getElementById("modal_img");
    var captionText = document.getElementById("caption");

    $(".responsive img").click(
      function(event){         
          if(event.target == this){
            openModal();
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
          }
      }
    );

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() { 
        closeModal();
    }

    // Click outside modal to close modal : 
    modal.onclick = function(event) { 
       if (event.target == modal) { // must use "event.target" otherwise modal closes on clicking the image
           closeModal();
       }
    }   

  }, 

  render: function() {
      var html_object = { file_path : "image.html"} ;
      return (
        <LoadInnerHtml  html= {html_object}  afterRender={this.afterRender}   />                 
      );
  }

});


///
var componentGMAP = React.createClass({

  afterRender: function() {

    function getLocation() {

        /* Must use with HTTPS
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            alert("Geolocation is not supported by this browser.");
        }
        */

        $.get("http://ipinfo.io", function (response) {
          /*
            {
            "ip": "49.177.226.68",
            "hostname": "No Hostname",
            "city": "Wantirna South",
            "region": "Victoria",
            "country": "AU",
            "loc": "-37.8833,145.2167",
            "org": "AS4804 Microplex Network Operations Centre",
            "postal": "3152"
            }
          */
            var res = response.loc.split(",");
            showPosition( parseFloat(res[0]) , parseFloat(res[1]) );
        }, "jsonp");

    }

    function showPosition(lat, lng) {
        console.log(lat); console.log(lng);

        var myLatLng = {lat: lat, lng:  lng};
        //myLatLng.lat = position.coords.latitude;
        //myLatLng.lng = position.coords.longitude;
        
        var mapOptions = {
            center: new google.maps.LatLng(myLatLng.lat, myLatLng.lng),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById("map_div"), mapOptions);

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Hello World!'
        });

    }
 
    getLocation();
    
  }, 

  render: function() {
      var html_object = { file_path : "gmap.html"} ;
      return (
          <LoadInnerHtml  html= {html_object}  afterRender={this.afterRender}   />              
      );
  }

});


///
var componentPhotoCropper = React.createClass({

  afterRender: function() {
    
    $("#cropper_add_photo_btn").click(
      function onClickAdd(e){
        e.preventDefault();
        $('#upload_modal').modal();
      }
    );


    $( "#hidden_modal" ).load( "cropper_modal_outter.html", 
      function(){    
        $( "#upload_modal_inner" ).load( "cropper_modal.html", 
          function(){
            // load js files 
            var element1 = document.createElement("script");
            element1.src = "js/cropper/common.js";
            document.body.appendChild(element1);
            var element2 = document.createElement("script");
            element2.src = "js/cropper/cropper.min.js";
            document.body.appendChild(element2);
            var element3 = document.createElement("script");
            element3.src = "js/cropper/main.js";
            document.body.appendChild(element3);
          } 
        );
      } 
    );

    $('#hidden_modal').css({ display: "block" });
    
  }, 

  render: function() {
      var html_object = { file_path : "cropper.html"} ;
      return (
        <LoadInnerHtml  html= {html_object}  afterRender={this.afterRender}   />                 
      );
  }

});


var componentChooser = React.createClass({
  render: function() {
      return (
         <ServiceChooser items={ services } />
      );
    }
});

var componentUnlimitedShow = React.createClass({

  
  componentDidMount: function() {
      //console.log("componentDidMount");
      //console.log(state);
           

      setState(
      state.hasOwnProperty('contacts')
      ? 
        { 
          contacts: state.contacts,         
          newContact: Object.assign({}, CONTACT_TEMPLATE),
        }
        :
        {
          contacts: [
            {key: 1, name: "James", email: "James's post"},
            {key: 2, name: "Jim", email: "Jim's post"},
            {key: 3, name: "James", email: "James's post"},
            {key: 4, name: "Jim", email: "Jim's post"},
            {key: 5, name: "James", email: "James's post"},
            {key: 6, name: "Jim", email: "Jim's post"},
            {key: 7, name: "James", email: "James's post"},
            {key: 8, name: "Jim", email: "Jim's post"},
            {key: 9, name: "James", email: "James's post"},
            {key: 10, name: "Jim", email: "Jim's post"},
            {key: 11, name: "James", email: "James's post"},
            {key: 12, name: "Jim", email: "Jim's post"},
          ],
          newContact: Object.assign({}, CONTACT_TEMPLATE),
        }      
    );

  }, 
  

  render: function() {

      return (
        <div id = "divtest2">
         
        </div>           
      );
    }
});


var componentSearch = React.createClass({
  render: function() {
      return (
        <div>
          <h1>Search</h1>
          <SearchExample items={ libraries } />
        </div>
      );
    }
});




var App = React.createClass({
  render: function() {
    return (
      <div className="content">
          {this.props.children}
      </div>
    )
  }
});
 





/// START : Initial React Render....
ReactDOM.render(
  <Router>
    <Route path="/" component={App}>

        <IndexRoute component={componentAbout}/> 
       
        <Route path="chooser" component={componentChooser} />
        <Route path="unlimitedshow" component={componentUnlimitedShow} />      
        <Route path="search" component={componentSearch} />
        <Route path="imagegallery" component={componentImageGallery} />
        <Route path="gmap" component={componentGMAP} />
        <Route path="cropper" component={componentPhotoCropper} />

    </Route>
  </Router>,
  document.querySelector("#middleDivRow")
);

$("#middleDiv").css({ "visibility": "visible",  }); // if we don't this, html foot will show before React rendered

/// END : Initial React Render....








