

/**
 * Get the key for matched value.
 * 
 * Syntax: {} and pushing values into it 
 *
 * @return mixed The key for the first matched value
 **/
function getKeyByValue(object, value) {
    return Object.keys(object).find(key => object[key] === value);
}
