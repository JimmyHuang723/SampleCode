export function setName(name) {

/*
    return {
        type: "SET_NAME",
        payload: name
    };
*/

    // return dispatch => {
    //     // Simulate ajax...
    //     setTimeout(() => {
    //         dispatch({
    //             type: "SET_NAME",
    //             payload: name
    //         });
    //     }, 2000);
    // }

    return {
        type: "SET_NAME",
        payload: new Promise((resolve, reject) => {
            // Simulate ajax...
            setTimeout(() => {
                resolve(name);
            }, 2000);
        })
    };
}

export function setXXX(xxx) {

    return {
        type: "SET_XXX",
        payload: xxx
    };

    /*
    return dispatch => {
         // Simulate ajax...
         setTimeout(() => {
             dispatch({
                 type: "SET_XXX",
                 payload: xxx
             });
         }, 2000);
    }
    */

    /*
    return {
        type: "SET_XXX",
        payload: new Promise((resolve, reject) => {
            // Simulate ajax...
            setTimeout(() => {
                resolve(name);
            }, 2000);
        })
    };
    */
}

export function setAge(age) {
    return {
        type: "SET_AGE",
        payload: age
    };
}