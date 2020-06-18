<?php include('includes/header.php'); ?>

<div class = "container" style = "padding-top: 80; background-color: lightblue;"> 
<ul class="nav" id = "nav" style = "margin-top: 80px;">
  <li class="nav-item">
    <a class="nav-link active" id = "books" href = "?page=books">All Books</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id = "lent" href = "?page=lentBooks">Books Lent</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id = "members" href = "?page=members">Members</a>
  </li> 
  <li class = "nav-item" style = "color: red">
    <a class = "nav-link" id = "members" href = "?page=lateBooks" style = "color: red;">Late Books</a>
  </li>
</ul>

    <div id = 'scrollable-container' style = "max-height: 400px; overflow: scroll;">
        <?php 
            $this->get_data('container-data');
        ?>
    </div>

</div>

<script>
    // alert('hi');
</script>

<script>
    var selectedIndex = <?php $this->get_data('selected_index'); ?> 

    window.onload = function() {
        var links = document.getElementsByClassName('nav-link'); 
        console.log(links);
        console.log('sindex: ' + selectedIndex);
        if (selectedIndex == 2) {
            var rows = document.querySelectorAll('tr');  
            console.log(3); 
            var indexArray = []  

            function buildQueryString(index) {
                console.log('index: ' + index)
                return '&member=' + index;
            }
            
            const queryStrings = [];
            for (var i = 0; i < rows.length; i++) {
                queryStrings.push(buildQueryString(i))
            } 
            console.log('queryStrings: ' + queryStrings) 
            queryStrings.forEach(function(queryString, index) {
                rows[index].addEventListener('click', function() {
                    window.location = window.location + queryString;
                })
            })
        }
        console.log('selectedIndex: ' + selectedIndex);
        for (var i = 0; i <= links.length; i++) { 
            let link = links[i];
            console.log('i: ' + i) 
            console.log('selectedIndex: ' + selectedIndex);
            if (i == selectedIndex) {
                links[i].parentNode.style.backgroundColor = '#4287f5';
                links[i].style.color = 'whitesmoke' 
            } else { 
                links[i].parentNode.style.backgroundColor = 'whitesmoke';
            }
        }
    } 
    
</script>

<style>
    #scrollable-container {
        overflow: hidden;
    }
</style>


<?php include('includes/footer.php'); ?>