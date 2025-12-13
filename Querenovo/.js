
var myCode = `

function learnJavaScript() {
    setTimeout(() => {
        console.log("JavaScript Learned");
        learnReact(); // React only after JS
    }, 2000);
}

function learnReact() {
    setTimeout(() => {
        console.log("React Learned");
    }, 1000);
}

learnJavaScript();

// Do not touch the code below:
module.exports = {
  learnJavaScript,
  learnReact,
};


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
