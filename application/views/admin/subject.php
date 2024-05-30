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
                        if($subject['id'] == $subject_id){
                            $current_subject = $subject;
                        }
                        echo '<a href="/admin/subject/'.$subject['id'].'" class="block py-2 px-5 '.($subject['id'] != $subject_id ? 'hover:' : '').'bg-slate-800 hover:text-slate-50">'.$subject['name'].'</a>';
                    }
                    ?>
                    <a href="/admin/ranking" class="block py-2 px-5 hover:bg-slate-800 hover:text-slate-50">Student ranking</a>
                </div>

                <button id="logout" class="w-full py-2 px-5 bg-red-500 hover:bg-red-600 text-slate-50">Logout</button>
            </div>

            <!-- main -->
            <div class="w-full bg-slate-100 p-2 h-full overflow-auto relative">

                <div class="block p-2 bg-slate-50 rounded shadow text-sm">

                    <div class="block mt-4 border rounded overflow-clip shadow">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b bg-slate-600 text-slate-50">
                                    <th>name</th>
                                    <th>grade</th>
                                    <th>remarks</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                
                            </tbody>
                        </table>
                    </div>

                    
                    <div class="block mt-2" wire:ignore>
                        <div class="max-w-[500px] mx-auto"><canvas id="students-chart"></canvas></div>
                    </div>

                    <div class="block text-end">
                        <button id="export-excel" class=" bg-blue-500 hover:bg-blue-600 text-white px-2 rounded py-1">
                            Export Excel
                        </button>
                        <button id="export-pdf" class=" bg-orange-500 hover:bg-orange-600 text-white px-2 rounded py-1">
                            Export PDF
                        </button>
                    </div>

                    <h2 class="text-lg mb-2 mt-4">Import Sheet</h2>

                    <div class="block">
                        <input type="file" id="import" name="import"
                        class="border p-0.5 rounded outline-slate-500 text-sm 
                        bg-slate-400 text-white 
                        file:bg-slate-200 file:hover:bg-slate-300 
                        file:border-0 file:rounded file:text-slate-800
                        file:cursor-pointer">
                        <button id="upload-file" class=" bg-blue-500 hover:bg-blue-600 text-white px-2 rounded py-1">
                            Upload file
                        </button>
                    </div>

                    <div id="file-error" class="hidden p-2 rounded mt-2 border border-red-400 bg-red-50 text-sm text-red-500">
                        {{$message}}
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

            const studentsChart = new Chart("students-chart", {
                type: "bar",
                data: {
                    labels: ["Ungraded", "Failed", "Passed"],
                    datasets: [{
                        label: 'Students Remarks',
                        backgroundColor: ["gray","red","green"],
                        data: [
                            0,0,0,0
                        ]
                    }]
                },
                options: {}
            });

            $('#logout').on('click', function(){
                displayLoader('loader');

                ajax(host+'/api/logout', 'GET', getCookie('api_token'), function(data){
                    location.reload();
                });
            });

            function updateGrades(){
                $('#table-body').html('');
                displayLoader('main-loader');

                ajax(host+'/api/grades/<?php echo $subject_id; ?>', 'GET', getCookie('api_token'), function(data){
                    let ungraded = 0;
                    let passed = 0;
                    let failed = 0;

                    data.forEach(function(grade){
                        if(grade.grade == null){
                            ungraded++;
                        }else if(grade.grade > 74){
                            passed++;
                        }else{
                            failed++;
                        }

                        let table_row = `
                            <tr>\
                                <td class="px-2">${grade.student.name}</td>\
                                <td class="text-center">${grade.grade != null ? grade.grade : 'ungraded'}</td>\
                                <td class="text-center \
                                ${grade.grade == null ? '' : (grade.grade > 74 ? 'text-white bg-green-500' : 'text-white bg-red-500') }
                                ">${grade.grade == null ? 'ungraded' : (grade.grade > 74 ? 'PASSED' : 'FAILED') }</td>\
                            </tr>`;

                        $('#table-body').append(table_row);
                    });
                    
                    studentsChart.data.datasets[0].data = [ungraded, failed, passed, 0];
                    studentsChart.update();

                    displayLoader('main-loader', false);

                }, function(data){
                    console.log(data);
                });
                
            }

            $('#export-excel').on('click', function(){
                displayLoader('main-loader');

                downloadFileAPI(host+'/api/grades/export/<?=$subject_id?>', 'Grades-<?=$current_subject['name']?>.xlsx', function(data){
                    displayLoader('main-loader', false);
                });
            });

            $('#export-pdf').on('click', function(){
                displayLoader('main-loader');

                downloadFileAPI(host+'/api/grades/export/pdf/<?=$subject_id?>', 'Grades-<?=$current_subject['name']?>.pdf', function(data){
                    displayLoader('main-loader', false);
                });

            });

            $('#upload-file').on('click', function(){
                displayLoader('main-loader');

                var myFormData = new FormData();
                myFormData.append('file', document.getElementById("import").files[0]);

                $.ajax({
                    url: host+'/api/grades/import/<?=$subject_id?>',
                    type: 'POST',
                    headers:{
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + getCookie('api_token'),
                    },
                    processData: false,
                    contentType: false,
                    dataType : 'json',
                    data: myFormData,
                    success: function(data){
                        if(data.success){
                            $('#file-error').hide();
                            
                            if(Object.hasOwn(data.errors)){
                                $('#file-error').show().html('');

                                for (const [k, v] of Object.entries(data.errors)){
                                    $('#file-error').append(`
                                        <li>${k}: ${v}</li>\
                                    `);
                                }
                            }

                            updateGrades();
                        }else{
                            $('#file-error').show().html('');

                            for (const [k, v] of Object.entries(data.errors)){
                                $('#file-error').append(`
                                    <li>${k}: ${v}</li>\
                                `);
                            }
                        }
                        console.log(data);
                        displayLoader('main-loader', false);
                    },
                }).fail(function(data){
                    console.log(data);
                });

            });
            
            updateGrades();
        </script>
    </footer>
</html>