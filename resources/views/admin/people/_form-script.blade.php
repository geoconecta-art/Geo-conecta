<script>
    $('select[name=zip_code]').select2();

    $('select[name=person_id]').select2();

    $('input[name=phone]').inputmask('9999999999');
    
    $('input[name=ine]').inputmask('AAAAAA99999999A999');

    $('select[name=zip_code]').on('change', function () {
        let zipCode = $(this).val();
        let _token = $token.val();
        $loading.show();

        $.post('/people/get-suburbs-options', { '_token':_token, 'zip_code':zipCode }, function(data) {
            $('select[name=suburb_id]').html(data);
            $loading.hide();
        });
    });

    /*
    function validateByBlock() {

        let section = $('input[name=section]').val();
        let block = $('select[name=block]').val();
        let _token = $token.val();
        $loading.show();
    
        $.post('/promoted/validate-by-block', { 
            '_token':_token, 
            'section':section,
            'block':block  
        }, function(response) {
            $loading.hide();
            if ( !response.success ) {
                showErrorModal(response.error);
                return false;
            }

        });

        return true;
        
    }

    document.getElementById('personForm').addEventListener('submit', function(event) {
        if ( !validateForm() ) {
            event.preventDefault(); 
            setInputErrorKeyup();
        }

        $loading.show();
    });
    
    function validateForm() {
            var ineInput = $('input[name=ine]');
            var phoneInput = $('input[name=phone]');
            
            if ( !validateINE(ineInput) || !validatePhone(phoneInput) || !validateByBlock() ) {
                event.preventDefault(); 
            }
        }

    */


</script>