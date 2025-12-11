var myCode = `

function planTrip() {
    setTimeout(() => {
        console.log("Trip to Ladakh planned");
    }, 500);
}

function buyBike() {
    setTimeout(() => {
        console.log("Bought Royal Enfield Himalayan");
        planTrip(); 
    }, 1000); 
}

module.exports = {
    buyBike,
    planTrip
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
