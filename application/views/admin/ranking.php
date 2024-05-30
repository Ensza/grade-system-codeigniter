<html>
    <?php $this->load->view('head'); ?>
    <body>
        <div class="flex w-full h-full text-sm flex-col md:flex-row relative">
            <div class="w-full md:w-[20em] bg-slate-700 flex flex-col">
                <h2 class="text-end text-xl me-5 mt-4 text-slate-50 font-light">Admin</h2>
                <div class="mt-4 text-end text-slate-200 h-full">
                    <?php
                    $current_subject;
                    foreach($subjects as $subject){
                        echo '<a href="/admin/subject/'.$subject['id'].'" class="block py-2 px-5 hover:bg-slate-800 hover:text-slate-50">'.$subject['name'].'</a>';
                    }
                    ?>
                    <a href="/admin/ranking" class="block py-2 px-5 bg-slate-800 hover:text-slate-50">Student ranking</a>
                </div>

                <button id="logout" class="w-full py-2 px-5 bg-red-500 hover:bg-red-600 text-slate-50">Logout</button>
            </div>

            <!-- main -->
            <div class="w-full bg-slate-100 p-2 h-full overflow-auto relative">

                <div class="flex flex-col md:flex-row w-full rounded bg-slate-50 shadow text-slate-600 p-2">
                    <div class="p-4 border-e">
                        <h2 class="text-lg mb-4">Minimum grades for ranking</h2>
                        <div class="grid grid-cols-2">

                            <?php foreach($subjects as $subject):?>
                            <div class="inline-flex items-center"><label for=""><?=$subject['name']?></label></div>
                            <input type="" subject-id="<?=$subject['id']?>" class="py-1 px-3 rounded-full border mb-2 text-center subject-min-grade" value="<?=$subject['ranking_min_grade']?>">
                            <?php endforeach;?>
                            
                            <div class="inline-flex items-center"><label for="">Minimum GWA</label></div>
                            <input type="" id="min-gwa" class="py-1 px-3 rounded-full border mb-2 text-center mt-4" value="85">

                        </div>
                        <button id="update-ranking" class="rounded-full py-1 px-2 w-full bg-blue-500 hover:bg-blue-600 text-white mt-4">Update</button>
                        <div id="error-min-grades" style="display: none;" class="p-2 rounded border border-red-500 bg-red-50 text-red-500 mt-2">

                        </div>
                    </div>

                    <div class="p-4 w-full">
                        <h2 class="text-lg mb-4">Ranking</h2>
                        <div class="block mt-4 border rounded overflow-clip shadow">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b bg-slate-600 text-slate-50">
                                        <th>Rank</th>
                                        <th>Name</th>
                                        <th>GWA</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="block text-end">
                            <button id="download-ranking-pdf" class="rounded py-1 px-2 bg-orange-500 hover:bg-orange-600 text-white mt-4">Download PDF</button>
                        </div>
                    </div>
                </div>

                <div id="main-loader"
                    class="absolute w-full h-full bg-white bg-opacity-50 top-0 left-0 flex items-center" 
                    style="display: none;">
                    <div class="loader mx-auto"></div>
                </div>

            </div>

            <?php $this->load->view('loader'); ?>
        </div>
    </body>
    <footer>
        <script>
            var host = '<?=$this->config->item('api_host')?>';

            $('#logout').on('click', function(){
                displayLoader('loader');

                ajax(host+'/api/logout', 'GET', getCookie('api_token'), function(data){
                    location.reload();
                });
            });

            $('#update-ranking').on('click', function(){
                updateRanking();
            });
            
            $('#download-ranking-pdf').on('click', function(){
                displayLoader('main-loader');

                let arr = {subjects: [], min_gwa: $('#min-gwa').val()};

                $('.subject-min-grade').each(function($element){
                    arr.subjects.push({subject_id: $(this).attr('subject-id'), value: $(this).val()});
                });
                
                downloadFileAPI(host+'/api/export/ranking', 'ranking.pdf', function(data){
                    console.log(data);
                    displayLoader('main-loader', false);
                }, arr);
            });

            function updateRanking(){
                $('#error-min-grades').hide();
                displayLoader('main-loader');

                let arr = {subjects: [], min_gwa: $('#min-gwa').val()};

                $('.subject-min-grade').each(function($element){
                    arr.subjects.push({subject_id: $(this).attr('subject-id'), value: $(this).val()});
                });

                ajaxWithData(host+'/api/get-ranking', 'POST', getCookie('api_token'), arr, function(data){
                    $('#table-body').html('');

                    data.forEach(function(item, i){
                        let row =   `<tr class="${i+1 == 1 ? 'bg-yellow-200' : 
                                        i+1 == 2 ? 'bg-gray-200' :
                                        i+1 == 3 ? 'bg-orange-100' : ''}">
                                        <td class="p-2 text-center">${i+1}</th>
                                        <td class="p-2 text-center">${item.name}</th>
                                        <td class="p-2 text-center">${Math.round((item.grades_avg_grade + Number.EPSILON) * 100) / 100}</th>
                                    </tr>`;

                        $('#table-body').append(row);
                    });
                    
                    displayLoader('main-loader', false);
                }, function(data){
                    console.log(data);
                    $('#error-min-grades').html(data.responseJSON.error);
                    $('#error-min-grades').show();

                    displayLoader('main-loader', false);
                });
            }

            updateRanking();
        </script>
    </footer>
</html>