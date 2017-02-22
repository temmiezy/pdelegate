<!-- START PAGE SIDEBAR -->    <?php require_once'/../temps/sidebar.php'; ?>    <!-- END PAGE SIDEBAR -->

                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                                <div class="body-content">
                                    
                                    <div class="single-product">
                                        <div class="product-overview-area col-lg-12 col-md-12 col-sm-12">
                                            
                                           <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Description</th>
                                                        <th>Country</th>
                                                        <th>Currency</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
                                                    if($userRequests):
                                                        foreach ($userRequests as $userRequest):
                                                            
                                                            $i++;
                                                            if($edit === $userRequest->req_id)
                                                            {
                                                                echo '<tr>'.form_open("agents/editAgent/$agent->agent_id").'
                                                                        <td>Michael Bruce</td>
                                                                        <td>Javascript Developer</td>
                                                                        <td>Singapore</td>
                                                                        <td>29</td>
                                                                        <td>2011/06/27</td>
                                                                        <td>$183,000</td>
                                                                        '.form_close().'
                                                                    </tr>';
                                                            } else{
                                                                
                                                                echo '<tr>
                                                                        <td>'.$userRequest->date.'</td>
                                                                        <td>'.$userRequest->des.'</td>
                                                                        <td>'.$userRequest->country.'</td>
                                                                        <td>'.$userRequest->currency.'</td>
                                                                        <td>'.$userRequest->amount.'</td>
                                                                        <td>status</td>
                                                                    </tr>';
                                                                
                                                            }
                                                        
                                                    
                                                        endforeach; 

                                                   
                                                    endif;
                                                    ?>
                                                </tbody>
                                            </table>
                                            <br/><br/><br/><br/><br/><br/><br/><br/>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    
                                    
                                    
                                </div>
                                
                            </div>




                        </div>
                </div>
            </div>
            <!-- Shop page End Here -->