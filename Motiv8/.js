
function add(value, callback) {
  // complete this function
  setTimeout(()=>{
    value = value+3;
    console.log('Added 3');
    callback(value);
  },2000)
}


function multiply(value, callback) {
   // complete this function
   setTimeout(()=>{
    value = value*2;
    console.log('Multiplied by 2');
    callback(value);
  },1000)
}


 

// don't change it
const initialValue = 2;


// create callback hell 
add(initialValue, (resultAfterAdd) =>{
    multiply(resultAfterAdd,(finalResult)=>{
         console.log("Final result:", finalResult);
    })
}); 






