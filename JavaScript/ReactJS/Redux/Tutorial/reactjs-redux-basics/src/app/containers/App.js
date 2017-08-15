import React from "react";
import {connect} from "react-redux";

import { User } from "../components/User";
import { Main } from "../components/Main";
import { setName, setXXX } from "../actions/userActions";

class App extends React.Component {
    render() {
        return (
            <div className="container">
                <Main changeUsername={() => this.props.setYYY("Anna")}/>
             
                <User username={this.props.name}/>
                <User username={this.props.xxx}/>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
  return {
      //user: state.user,
      //math: state.math,
      name: state.user.name,
      xxx : "yyy"
  };
};

const mapDispatchToProps = (dispatch) => {
    return {
        setName: (name) => {
            dispatch(setName(name));
        },
        setXXX: (xxx) => {
            dispatch(setXXX(xxx));
        },
        setYYY: (yyy) => {

            dispatch(
                     {
                      type: "SET_NAME",
                      payload: "123"
                     }
                    );

            // Simulate ajax...
            setTimeout(() => {
                 dispatch({
                     type: "SET_XXX",
                     payload: "456"
                 });
            }, 2000);

            // Simulate ajax...
            setTimeout(() => {
                 dispatch({
                     type: "SET_YYY",
                     payload: "789"
                 });
            }, 4000);

        }
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(App);
