<script>
    
    $('input[name=phone]').inputmask('9999999999');
    $('input[name=link_phone]').inputmask('9999999999');

    $('select[name=region]').on('change', function () {
        var dependence =  $('select[name=region] option:selected').attr('dep');
        $('input[name=dependence]').val(dependence);
    });

    function setName() {
        var firstName = document.getElementById('firstName').value;
        var lastName1 = document.getElementById('lastName1').value;
        var lastName2 = document.getElementById('lastName2').value;
        var name = firstName.trim() + ' ' + lastName1.trim() + ' ' + lastName2.trim();
        document.getElementById('name').value = name;
    }

    document.getElementById('personForm').addEventListener('submit', function(event) {
        
        setName();
        
        var phoneInput = $('input[name=phone]');
        var linkPhoneInput = $('input[name=link_phone]');
        
        if ( !validatePhone(phoneInput) || !validatePhone(linkPhoneInput) ) {
            event.preventDefault(); 
            setInputErrorKeyup();
        } else {
            $loading.show();
        }

        
    }); 

</script>