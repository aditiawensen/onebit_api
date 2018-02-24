function filter(event){
    if(event.checked){
        show(event.value);
    }else{
        hide(event.value);
    }
}

function show(category){
    for (i = 0; i < getmarkers.length; i++) {
        marker = getmarkers[i];
        if(marker.category.indexOf(category) >= 0){
            marker.setVisible(true);
        }
    }
}

function hide(category){
    for (i = 0; i < getmarkers.length; i++) {
        marker = getmarkers[i];
        if(marker.category.indexOf(category) >= 0){
            marker.setVisible(false);
        }
    }
}