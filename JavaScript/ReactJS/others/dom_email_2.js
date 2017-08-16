
/*
 * Components
 */


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
        React.createElement('img', {src : imgSrc, width : "20%", height:"20%"})
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

  // Added to fix misusage of render() to update UI. (should update state instead.)
  getInitialState: function(){

        this.sync_contacts = this.props.contacts;

        // This is called before our render function. The object that is 
        // returned is assigned to this.state, so we can use it later.

        return { 
                  newContact: 0,
                  contacts: this.props.contacts
               };
  },

  componentDidMount: function() {
    window.addEventListener("scroll", this.handleScroll);
  },

  handleScroll: function(event) {

      console.log("scrollllll!!!");
      console.log(event);

      const windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;      
      const body = document.body;
      const html = document.documentElement;
      const docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight,  html.scrollHeight, html.offsetHeight);
      const windowBottom = windowHeight + window.pageYOffset;
      //console.log('windowHeight : '.concat(windowHeight));
      //console.log('pageYOffset : '.concat(window.pageYOffset));
      //console.log('docHeight : '.concat(docHeight));
      if (windowBottom >= (docHeight - 5 )) {
        console.log('bottom reached');

        // Add item automatically when on bottom...
        var CONTACT_TEMPLATE_2 = {name: "xxx", email: "@gmail.com", description: "", errors: null};
        counter ++;
        CONTACT_TEMPLATE_2.name = counter; //"number ".concat(counter);
        var number = "number ".concat(counter);
        console.log(number);
        CONTACT_TEMPLATE_2.email = number.concat("s' post");

        // Offical site : don't assign new state based on current state as setState is async...
        tmp_states = this.state.contacts;
        tmp_states.push(CONTACT_TEMPLATE_2);
        this.sync_contacts.push(CONTACT_TEMPLATE_2);
        this.setState({
           newContact: Object.assign({}, CONTACT_TEMPLATE_2),
            //contacts: this.state.contacts.slice(0).concat(CONTACT_TEMPLATE_2)
           //contacts: tmp_states
           contacts: this.sync_contacts
        });

        // This is NOT a suggested way as we can just update state instead of calling render()
        //this.props.onNewContactChange(CONTACT_TEMPLATE_2);
        //this.props.onNewContactSubmit();

      } else {
        console.log('not at bottom');        
      }      

  },

  render: function() {
    var contactItemElements = this.state.contacts
      .map(function(contact) { return React.createElement(ContactItem, contact); });

    return (
      React.createElement('div', {className: 'ContactView'},
        React.createElement('h1', {className: 'ContactView-title'}, "Contacts"),
        React.createElement('ul', {className: 'ContactView-list'}, contactItemElements),
        React.createElement(ContactForm, {
          ref: 'contactform',
          value: this.state.newContact,
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

var counter = 0;

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
    document.getElementById('react-app')
  );
}

// Set initial data
setState({
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
});



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

