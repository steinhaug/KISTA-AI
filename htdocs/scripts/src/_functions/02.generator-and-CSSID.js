    /**
    * Deliver unique numerical numbers needed for IDs 
    */
    function* infinite() {
        let index = 0;

        while (true) {
            yield index++;
        }
    }
    const generator = infinite();

    /**
    * Return a valid document ID used by selectors
    *
    * @return string A valid HTML document ID
    */
    function getNewId(){
        return 'css' + generator.next().value
    }

    /**
    * Get the existing CSS ID, if not create a new one and set it
    * 
    * param node el The node/element to check for the ID
    * return string The CSS ID
    */
    function getCSSID(el){
        if( $(el).hasAttr('id') ){
            console.log('read');
            var CSSID = $(el).attr('id');
        } else {
            console.log('set');
            var CSSID = getNewId();
            $(el).attr('id',CSSID);
        }
        return CSSID;
    }
