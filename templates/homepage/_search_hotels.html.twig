<form id="searchHotelsForm" class="row g-3">

    <div class="col-md-2 col-sm-12">
        <label class="fs-xs" for="bhotel_check_in">Check in</label>
        <div class="date-picker-container">
            <input
                    type="text"
                    class="form-control bg-transparent fs-sm"
                    placeholder="inserisci data"
                    aria-label="inserisci data"
                    aria-describedby="bhotel_check_in"
                    id="bhotel_check_in"
                    name="bhotel[check_in]"
            />
            <div class="date-picker-ico"><i class="ci ci-sm ci-calendar"></i></div>
        </div>
    </div>
    <div class="col-md-2 col-sm-12">
        <label class="fs-xs" for="bhotel_check_out">Check out</label>

        <div class="date-picker-container">
            <input
                    type="text"
                    class="form-control bg-transparent fs-sm"
                    placeholder="inserisci data"
                    aria-label="inserisci data"
                    aria-describedby="bhotel_check_out"
                    id="bhotel_check_out"
                    name="bhotel[check_out]"
            />
            <div class="date-picker-ico"><i class="ci ci-sm ci-calendar"></i></div>
        </div>

    </div>
    <div class="col-md-5 col-sm-12">
        <label class="fs-xs" for="bhotel_rooms">Rooms &amp; Passengers</label>

        <select class="form-select fs-xxs" id="bhotel_rooms" name="bhotel[rooms]">
            <option value="1">1 rooms - 2adl + 1 chl (10y)</option>
        </select>

    </div>
    <div class="col-md-3 col-sm-12 align-self-end">
        <button type="submit"
                class="btn w-100 btn-primary text-uppercase text-white">cerca
        </button>
    </div>

</form>
<div class="my-2 text-center visually-hidden" id="searchHotelsResult"></div>
<script>

    const searchHotelsForm = document.getElementById('searchHotelsForm');
    const searchHotelsResult = document.getElementById('searchHotelsResult');

    searchHotelsForm.addEventListener('submit', (e) => {

        e.preventDefault();

        if(!searchHotelsResult.classList.contains('visually-hidden')){
            searchHotelsResult.classList.add('visually-hidden')
        }

        const formData = new FormData(e.target);
        const fromDate = formData.get('bhotel[check_in]');
        const toDate = formData.get('bhotel[check_out]');

        apiTravioSearchHotels(fromDate, toDate).then(function(result){
            if(result) {
                searchHotelsResult.innerText = 'Numero di risultati trovati: ' + result['tot'];
                searchHotelsResult.classList.remove('visually-hidden');
            }
        })

    }, {capture: true})


</script>