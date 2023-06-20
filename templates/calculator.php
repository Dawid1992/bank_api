<?php require_once('partials/header.php'); ?>

<div class="container">
    <div class="c_page">
        <div class="calculator">
            <div class="c_row">
                <div class="c_instruction">
                    <p>Rules for using the calculator:</p>
                    <ul>
                        <li>Enter the amount you want to convert</li>
                        <li>Select the source currency</li>
                        <li>Then select the target currency</li>
                        <li>After completing all fields, please press "Recalculate"</li>
                    </ul>
                    <p>Want to see previous searches? go to <a href="/history" class="btn">history</a></p>
                </div>
            </div>
            <div class="c_row">
                <div class="c_form_box">
                    <label for="amount">Amount:</label>
                    <input type="number" min="1" id="amount" placeholder="Input the amount">
                    <p class="error"></p>
                </div>
                <div class="c_form_box">
                    <label for="source_currency">Source currency:</label>
                    <select name="source_currency" id="source_currency">
                        <option selected disabled>Select</option>
                        <?php foreach($currencies as $currency): ?>
                            <option value="<?= $currency['id']; ?>" data-code="<?= $currency['code']; ?>"><?php echo htmlspecialchars($currency['currency']); ?></option>
                        <?php endforeach ?>
                    </select>
                    <p class="error"></p>
                </div>
                <div class="c_form_box">
                    <label for="target_currency">Target currency:</label>
                    <select name="target_currency" id="target_currency">
                        <option selected disabled>Select</option>
                        <?php foreach($currencies as $currency): ?>
                            <option value="<?= $currency['id']; ?>" data-code="<?= $currency['code']; ?>"><?php echo htmlspecialchars($currency['currency']); ?></option>
                        <?php endforeach ?>
                    </select>
                    <p class="error"></p>
                </div>
                <div class="c_form_box">
                    <button type="submit" class="c_form_btn">Convert</button>
                </div>
            </div>
            <div class="c_row">
                <p class="result_text">

                </p>
            </div>
        </div>
    </div>
</div>

<script>
    $('.c_form_btn').on('click',function(){
        var data = {
            amount: $('#amount').val(),
            source: $('#source_currency').val(),
            target: $('#target_currency').val()
        }
        
        $.ajax({
            type: "POST",
            url: "/convert",
            dataType: 'json',
            data: {data: data},
            success: function(result) {
                if(result.success){
                    var scode = $('#source_currency option:selected').attr('data-code');
                    var tcode = $('#target_currency option:selected').attr('data-code');
                    $('.result_text').text("After converting for your " + scode + " you can have " + result.new_amount + " " + tcode + ".")
                }
                else{
                    $('.result_text').text(result[0]);
                }
                
            },
            error: function(result) {

            }
        });
    });

    
</script>

<?php require_once('partials/footer.php'); ?>
