<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>

<script>
    let department_list = document.querySelector('.department-list');
    department_list.innerHTML = `<div class="alert alert-info alert-success-subtle">
                                    <strong>Info!</strong> loading departments.
                                </div>`
    let createList = (data) => {
        console.log(data);
        let html ='';
        data.forEach((item)=>{
             html += `<a href="/resultats?department_code=${item.dep_id}" class='atage'><span class="dep-tag col-sm-6">${item.dep_id} ${item.dep_name}</span></a>`
        })
        department_list.innerHTML = html;
    }
    let fetch_daprtments = async()=>{
        let reques = await axios.get('https://www.quirenov.fr/api/widget/json/post/par_departement_ajax')
        createList(reques.data);
    }
    fetch_daprtments();
</script>

<script>
    const searchInput = document.querySelector('.box2 input[type="text"]');

    searchInput.addEventListener('keyup', function(e) {
        const filterText = e.target.value.toLowerCase().trim();
        const departmentLinks = document.querySelectorAll('.department-list a');

        departmentLinks.forEach(function(link) {
            const departmentName = link.textContent || link.innerText;
            if (departmentName.toLowerCase().indexOf(filterText) > -1) {
                link.style.display = ""; 
            } else {
                link.style.display = "none";
            }
        });
    });
</script>