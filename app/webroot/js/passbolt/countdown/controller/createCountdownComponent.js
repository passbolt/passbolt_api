steal( 
    'lb/core/controller/componentController.js'
)
.then( 
    function($){
        
        lb.core.controller.ComponentController.extend('passbolt.countdown.controller.CreateCountdownComponent', {}
        , {    
            'init' : function(el)
            {
                this._super();
                this.inputDefaultValues = {};
                
                //Init the date picker
                $('#CountdownDate').datepicker({
                        ampm: true
                });
                //Init the time picker
                $('#CountdownTime').datepicker({
                        ampm: true
                });
            }
            
            /**
             * If the input elements of the form are geting focus, clean the value
             * it is the default value
             */
            , 'input[type="text"] focus' : function(elt, event)
            {
                //Store the default value of the component if not done yet
                if(typeof this.inputDefaultValues[elt.attr('id')] == 'undefined'){
                    this.inputDefaultValues[elt.attr('id')] = elt.attr('value');
                }
                
                //If the element value is the default value clean it
                if(elt.val() == this.inputDefaultValues[elt.attr('id')]){
                    elt.val('');
                    elt.removeClass('gacd-input-defaultValue');
                }
            }
            
            /**
             * If the input elements of the form are loosing focus, add the default
             * value if the element's value is empty
             */
            , 'input[type="text"] blur' : function(elt, event)
            {
                //If the field is empty, put the default value inside
                if($.trim(elt.val()) == ''){
                    elt.val(this.inputDefaultValues[elt.attr('id')]);
                    elt.addClass('gacd-input-defaultValue');
                }
            }
            
        });
        
    }
);
