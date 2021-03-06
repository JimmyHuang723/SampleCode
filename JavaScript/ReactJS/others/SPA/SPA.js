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
    return (
      React.createElement('li', {className: 'ContactItem'},
        React.createElement('h2', {className: 'ContactItem-email'}, this.props.email),
        React.createElement('span', {className: 'ContactItem-name'}, this.props.name)
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
    window.addEventListener("scroll", this.handleScroll);
  },

  handleScroll: function(event) {

      //console.log("scrollllll!!!");
      //console.log(event);

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
        counter ++;
        CONTACT_TEMPLATE_2.name = "number ".concat(counter);
        CONTACT_TEMPLATE_2.email = CONTACT_TEMPLATE_2.name.concat("s' post");
        this.props.onNewContactChange(CONTACT_TEMPLATE_2);
        this.props.onNewContactSubmit();

      } else {
        console.log('not at bottom');        
      }      

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
    document.getElementById('divtest2')
  );

}

/*
// Set initial data
setState({
  contacts: [
    {key: 1, name: "James K Nelson - Front End Unicorn", email: "James's post"},
    {key: 2, name: "Jim", email: "Jim's post"},
    {key: 3, name: "James K Nelson - Front End Unicorn", email: "James's post"},
    {key: 4, name: "Jim", email: "Jim's post"},
    {key: 5, name: "James K Nelson - Front End Unicorn", email: "James's post"},
    {key: 6, name: "Jim", email: "Jim's post"},
    {key: 7, name: "James K Nelson - Front End Unicorn", email: "James's post"},
    {key: 8, name: "Jim", email: "Jim's post"},
    {key: 9, name: "James K Nelson - Front End Unicorn", email: "James's post"},
    {key: 10, name: "Jim", email: "Jim's post"},
    {key: 11, name: "James K Nelson - Front End Unicorn", email: "James's post"},
    {key: 12, name: "Jim", email: "Jim's post"},
  ],
  newContact: Object.assign({}, CONTACT_TEMPLATE),
});
*/


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



var { Router,
      Route,
      IndexRoute,
      IndexLink,
      Link } = ReactRouter; 

var destination = document.querySelector("#container");

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

var Test = React.createClass({
  render: function() {
      return (
         <ServiceChooser items={ services } />
      );
    }
});

var Test2 = React.createClass({

  
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
            {key: 1, name: "James K Nelson - Front End Unicorn", email: "James's post"},
            {key: 2, name: "Jim", email: "Jim's post"},
            {key: 3, name: "James K Nelson - Front End Unicorn", email: "James's post"},
            {key: 4, name: "Jim", email: "Jim's post"},
            {key: 5, name: "James K Nelson - Front End Unicorn", email: "James's post"},
            {key: 6, name: "Jim", email: "Jim's post"},
            {key: 7, name: "James K Nelson - Front End Unicorn", email: "James's post"},
            {key: 8, name: "Jim", email: "Jim's post"},
            {key: 9, name: "James K Nelson - Front End Unicorn", email: "James's post"},
            {key: 10, name: "Jim", email: "Jim's post"},
            {key: 11, name: "James K Nelson - Front End Unicorn", email: "James's post"},
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


var Search = React.createClass({
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
      <div>
        <h1>Jimmy's React.js test</h1>
        <ul className="header">
          <li><IndexLink to="/" activeClassName="active">Scroll Down</IndexLink></li>      
       
          <li><Link to="/test" activeClassName="active">Sum</Link></li>
       
          <li><Link to="/search" activeClassName="active">Search</Link></li>

        </ul>
        <div className="content">
            {this.props.children}
        </div>
      </div>
    )
  }
});
 




ReactDOM.render(
  <Router>
    <Route path="/" component={App}>
        <IndexRoute component={Test2}/> 
       
        <Route path="test" component={Test} />
      
        <Route path="search" component={Search} />
    </Route>
  </Router>,
  destination
);






