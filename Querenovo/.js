
var myCode = `


function checkCar() {
 return new Promise((resolve, reject) => {
  setTimeout(() => {
   const carReady = Math.random() > 0.5;
   if (carReady) {
    resolve("Car is ready");
   } else {
    reject("Error: Car needs maintenance");
   }
  }, 2000);
 });

}


function packForPicnic() {
 return new Promise((resolve, reject) => {
  setTimeout(() => {
   const itemsPacked = Math.random() > 0.5;
   if (itemsPacked) {
    resolve("Packed everything for picnic");
   } else {
    reject("Error: Not have some essentials");
   }
  }, 1000);
 });
}


async function picnicReady() {
  // Write your code here
  try{
    let result1 = await checkCar();
    console.log(result1);
    let result12 = await packForPicnic();
    console.log(result2);
  }catch(error){

  }
}

picnicReady();

// Do not touch the code below:
module.exports = { picnicReady };



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


should log "Packed everything for picnic" when the items are packed (4999 ms)

Error: expect(jest.fn()).toHaveBeenCalledWith(...expected) Expected: "Packed everything for picnic" Received: "Car is ready" Number of calls: 1