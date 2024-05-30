<html>
    <?php $this->load->view('head'); ?>
    <body>
        <div class="flex w-full h-full text-sm flex-col md:flex-row relative">
            <div class="w-full md:w-[20em] bg-slate-700 flex flex-col">
                <h2 class="text-end text-xl me-5 mt-4 text-slate-50 font-light">Student</h2>
                <div class="mt-4 text-end text-slate-200 h-full">
                    
                </div>

                <button id="logout" class="w-full py-2 px-5 bg-red-500 hover:bg-red-600 text-slate-50">Logout</button>
            </div>

            <!-- main -->
            <div class="w-full bg-slate-100 p-2 h-full overflow-auto relative">
                <h2 class="text-xl font-light mb-2">Subjects</h2>
                <div class="block bg-slate-50 rounded shadow p-2">
                    <div class="text-slate-600">
                        <span class="text-lg block"><?=$data['profile']['name']?></span>
                        <span class="block mt-4">Subjects: <?=count($data['grades'])?></span>
                    </div>
                    <div class="block mt-4 border rounded overflow-clip shadow mb-4">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b bg-slate-600 text-slate-50">
                                    <th>Subject</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <?php foreach($data['grades'] as $grade):?>
                                    <tr>
                                        <td class="px-2 text-center"><?php echo $grade['subject']['name']; ?></td>
                                        <td class="text-center"><?php echo $grade['grade'] != null ? $grade['grade'] : 'ungraded'; ?></td>
                                        <td class="text-center 
                                        <?php echo ($grade['grade'] == null ? '' : ($grade['grade'] > 5 ? 'text-white bg-green-500' : 'text-white bg-red-500')); ?>
                                        "><?php echo ($grade['grade'] == null ? 'ungraded' : ($grade['grade'] > 5 ? 'PASSED' : 'FAILED')); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="block text-end mb-4">
                        <button id="download-report" class="p-2 rounded text-slate-50 bg-blue-500 hover:bg-blue-600">
                            Download report card
                        </button>
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

            $('#download-report').on('click', function(){
                displayLoader('main-loader');

                downloadFileAPI(host+'/api/student/report-card', 'report-card.pdf', function(data){
                    displayLoader('main-loader', false);
                });

            });

            // ajax(host+'/api/student/grades', 'GET', getCookie('api_token'), function(data){
            //     console.log(data);
            // });
        </script>
    </footer>
</html>