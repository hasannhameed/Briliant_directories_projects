<script>


const codeToInject = `

   function findFirstDuplicateElement(arr, length) {
        let map = {};
        for(let i=0;i<arr.length;i++){
            if(map[arr[i]]){
                return arr[i];
            }else{
                map[arr[i]] = true;
            }
        }

    }
    

`;

if (window.monaco && window.monaco.editor) {
    const models = window.monaco.editor.getModels();
    
    if (models.length > 0) {
        models[0].setValue(codeToInject.trim());
        console.log(" Code injected via Monaco API!");
    } else {
        console.error("❌ No active editor models found.");
    }

} else {
    console.warn(" Monaco global object not found. Trying Option 2...");
    fallbackInjection(codeToInject);
}

function fallbackInjection(code) {
    const inputArea = document.querySelector('.monaco-editor textarea.inputarea');
    if (inputArea) {
        inputArea.focus();
        document.execCommand('selectAll'); 
        // Replace with new text
        document.execCommand('insertText', false, code.trim());
        console.log(" Code injected via DOM manipulation!");
    } else {
        console.error("Could not find the editor input area.");
    }
}

</script>