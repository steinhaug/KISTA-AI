function whichTransitionEvent(){

    var el = document.createElement('fakeelement');
    var transitions = {
        'animation':'animationend',
        'OAnimation':'oAnimationEnd',
        'MSAnimation':'MSAnimationEnd',
        'mozAnimation':'mozAnimationEnd',
        'WebkitAnimation':'webkitAnimationEnd'
    };

    for(var t in transitions){
        if( transitions.hasOwnProperty(t) && el.style[t] !== undefined ){
            return transitions[t];
        }
    }
}