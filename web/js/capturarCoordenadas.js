document.addEventListener('DOMContentLoaded', function () {

    var params = new URLSearchParams(window.location.search);
    var cx = params.get('coord_x');
    var cy = params.get('coord_y');

    if (cx && cy) {
        document.getElementById('coord_x').value        = cx;
        document.getElementById('coord_y').value        = cy;
        document.getElementById('coord_x_visual').value = cx;
        document.getElementById('coord_y_visual').value = cy;
    }
});