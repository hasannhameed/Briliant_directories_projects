
var myCode = `
function increment(value) {
    return new Promise((resolve) => {
        setTimeout(() => {
            const newValue = value + 1;
            resolve(newValue);
        }, 1200); 
    });
}

function double(value) {
    return new Promise((resolve) => {
        setTimeout(() => {
            const newValue = value * 2;
            resolve(newValue);
        }, 500);
    });
}

function square(value) {
    return new Promise((resolve) => {
        setTimeout(() => {
            const newValue = value * value;
            resolve(newValue);
        }, 1000); 
    });
}

// Starting value
const startValue = 2;
increment(startValue)
.then((value)=>{
    return double(value);
})
.then((vlaue)=>{
    return  square(vlaue);
}).then((value)=>{
    console.log(value);
})
// Execute functions in sequence using promises
 
`;


function injectCode() {
    
    if (window.monaco && window.monaco.editor) {
        var models = window.monaco.editor.getModels();
        var targetModel = models.find(m => m.uri.toString().includes("model/5"));
        if (!targetModel && models.length > 0) {
            targetModel = models[models.length - 1];
        }
        if (targetModel) {
            targetModel.setValue(myCode);
        } else {
        }
    }
}


injectCode();









