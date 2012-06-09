steal( 
    'jquery/class'
)
.then( 
    function($){
        
        /*
        * @class lb.core.model.Bootstrap
        * The ajax wrapper allows developper to make their ajax request, moreover
        * it allows them to make ajax transaction to minimize server calls by 
        * aggregating ajax requests 
        * @parent index
        * @constructor
        * Creates a new ajax transaction builder
        * @return {lb.core.controller.AjaxWrapper}
        */
        $.Class('lb.core.model.AjaxWrapper', 
                
        /** @static */
        
        {
            /**
             * Ajax wrapper instance.
             */
            'instance': null,
            
            /**
             * Get instance of the Ajax Wrapper singleton
             */
            'getInstance' : function()
            {
                return new lb.core.model.AjaxWrapper();
            }
        },
        
        /** @prototype */
        {
            
            /**
             * Pending transactions
             */
            'transactions': [],
            
            /**
             * Current transaction Id.
             */
            'transactionId': null,
            
            /**
             * Class Constructor. Singleton
             */
            'init': function(options)
            {
                if(lb.core.model.AjaxWrapper.instance != null){
                    return lb.core.model.AjaxWrapper.instance;
                }
            }
            
            /**
             * Perform an ajax request, parameters are similar to the ajax function of the jQuery
             * library. Add to this one a second parameter to define if the request has to be in 
             * a transaction or not !
             * @param {Array} request The ajax request object
             * @param {Boolean} transaction If set to true the system will try to bundle several
             * request in one.
             */
            , 'request': function(request, transaction)
            {
                if(transaction){
                    this._addRequest(request);
                    this._executeTransaction();
                }
                else{   
                    this._executeRequest(request);
                }
                
            }
            
            /**
             * Add a request to the current transaction
             * @private
             * @hide
             */
            , '_addRequest': function(request)
            {
                var transactionId = this._getTransactionId();
                if(typeof this.transactions[transactionId] == 'undefined'){
                    this.transactions[transactionId] = {
                        'requests' :        [],
                        'waitFor' :         0,
                        'transactionId':    transactionId
                    };
                }
                
                // Identify the request
                var requestId = uuid();
                request.id = requestId;
                this.transactions[transactionId].requests[requestId] = request;
                this.transactions[transactionId].waitFor++;
                steal.dev.log('pending requests : '+this.transactions[transactionId].waitFor);
            }
            
            /**
             * Generate a new current transaction id
             * @private
             * @hide
             */
            , '_generateTransactionId' : function()
            {
                this.transactionId = uuid();
            }
            
            /**
             * Get the current transaction id, generate a new one if null
             * @private
             * @hide
             */
            , '_getTransactionId': function()
            {
                if(this.transactionId == null){
                    this._generateTransactionId();
                }
                return this.transactionId;
            }
            
            /**
             * Get the current transaction
             * @private
             * @hide
             */
            , '_getTransaction': function(id)
            {
                var transactionId = typeof id != 'undefined' ? id : this._getTransactionId();
                return this.transactions[transactionId];
            }
            
            /**
             * Execute the requests transaction.
             * Here we use a timeout cycle to wait (1ms) on possible additional requests.
             * We have to test this method without console.log, which can make the code
             * execution sync ... etc ...
             * @private
             * @hide
             */
            , '_executeTransaction': function()
            {
                var self = this;
                setTimeout(function(){
                    // get the current transaction
                    var transaction = self._getTransaction();
                    // decrease the counter of pending request
                    transaction.waitFor--;
                    // @debug
                    steal.dev.log('pending requests : '+transaction.waitFor);
                    // if no more pending request, launche the transaction
                    if(transaction.waitFor == 0){
                        // reset transaction id to allow new transaction
                        self._generateTransactionId();
                        // build the bundled request
                        var bundleRequests = self._bundleRequests(transaction.requests);
                        // execute the bundled request
                        self._executeRequest({
                            type:       'POST',
                            url:        lb.APP_ROOT_URL+'/ajax/requests',
                            data:       {
                                'requests':         bundleRequests,
                                'transactionId':    transaction.transactionId
                            },
                            dataType:   'json',
                            success:    function(srvData)
                            {
                                var transactionId = srvData.transactionId;
                                var transaction = self._getTransaction(transactionId);
                                for(var requestId in transaction.requests){
                                    // execute success function of the stored request
                                    if(typeof transaction.requests[requestId].success != 'undefined'){
                                        transaction.requests[requestId].success(srvData.requests[requestId].data);
                                    }
                                }
                                
                                // remove the transaction
                                delete transaction;
                                delete self.transactions[transactionId];
                            }
                        });
                        
                    }
                }, 1);   // wait 1 ms seems to be enough
               
            }
            
            /**
             * Execute a request. Bundled or not
             * @private
             * @hide
             */
            , '_executeRequest': function(request)
            {
                request.type = typeof request.type == 'undefined' ? 'POST' : request.type ;
                steal.dev.log('request : '+request.url);
                return $.ajax(request);
            }
            
            /**
             * Bundle requests
             * @private
             * @hide
             */
            , '_bundleRequests': function(requests)
            {
                var bundle = [];
                for(var i in requests){
                    bundle.push({
                        'id': requests[i].id,
                        'url': requests[i].url,
                        'data': requests[i].data
                    });
                }
                return bundle;
            }
            
        });
        
    }
);
