<html>
<head>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.1.0.min.js"></script>
    <style>
        body {
            top:50%;
            left:50%;
            margin-top:50px;
            margin-left:100px;
        }
    </style>
</head>
<body>
<div class="row" id="newItemContainer">
    <form id="newItem">

        <div id="container">
            <div class="col-md-2 col-sm-2 form-group">
                <label for="productName">Product Name</label>
                <input type="text" class="form-control" name="productName">
            </div>
            <div class="col-md-2 col-sm-2 form-group">
                <label for="qtyInStock">Quantity in Stock</label>
                <input type="text" class="form-control" name="qtyInStock">
            </div>
            <div class="col-md-2 col-sm-2 form-group">
                <label for="pricePerItem">Price per item</label>
                <input type="text" class="form-control" name="pricePerItem">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>

    </form>
</div>
<div class="row" id="itemReportContainer">
    <form action="" id="reportForm">

        <input type="hidden" name="action" value="updateOnly">
        <div id="report"></div>

    </form>
</div>

<script type="text/javascript">
    function displayResults()
    {
        $.ajax({
            url: './display.php',
            type: 'GET',
            success: function (data) {
                $('#report').empty().html(data);
            }
        });
    }
    // after document loaded monitor for activities
    $(document).ready(
        function()
        {
            $('form#reportForm').on('focusout',
                function(event)
                {
                    var data = $(this.name);
                    console.log(data); return false;
                    $.ajax({
                        url : './process.php',
                        type : 'POST',
                        dataType : 'JSON',
                        data : data,
                    });
                }
            );

            displayResults();

            // when user clicks submit button
            $('form#newItem').submit(
                function (e)
                {
                    // prevent from default submission action
                    e.preventDefault();
                    $.ajax({
                        url : './process.php',
                        type : 'POST',
                        dataType : 'JSON',
                        data : $('form#newItem').serialize(),
                        success: function(data)
                        {
                            if(data.status == 'success')
                            {
                                displayResults();
                            }
                        }
                    });
                }
            )
        } // function
    ); // document.ready
</script>
</body>
</html>
