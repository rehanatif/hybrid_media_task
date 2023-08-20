<div class="modal" tabindex="-1" id="md_add_event">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('add_event')}}" method="post" enctype="multipart/form-data" id="form_md_add_event">
                @csrf
                <div class="modal-body">
                    <div class="row p-1">
                        <div class="col-md-12">
                            @php($name = 'subject')
                            @php($label = 'Subject')
                            <div class="my-1">
                                <label for="">{{$label}} <i class="text-danger">*</i></label>
                                <textarea name="{{$name}}" class="form-control cls_required" placeholder="{{$label}}"></textarea>                      
                                <small class="form-errors pull-right req text-danger" value="*">{{ $errors->first($name,":message") }}</small>
                            </div>
                        </div>    
                        <div class="col-md-6 ">
                            @php($name = 'start_date')
                            @php($label = 'Start Date')
                            <div class="my-1">
                                <label for="">{{$label}} <i class="text-danger">*</i></label>
                                <input type="datetime-local" name="{{$name}}" value="" class="form-control cls_required" placeholder="{{$label}}">                      
                                <small class="form-errors pull-right req text-danger" value="*">{{ $errors->first($name,":message") }}</small>
                            </div>
                        </div>    
                        <div class="col-md-6 ">
                            @php($name = 'end_date')
                            @php($label = 'End Date')
                            <div class="my-1">
                                <label for="">{{$label}} <i class="text-danger">*</i></label>
                                <input type="datetime-local" name="{{$name}}" value="" class="form-control cls_required" placeholder="{{$label}}">                      
                                <small class="form-errors pull-right req text-danger" value="*">{{ $errors->first($name,":message") }}</small>
                            </div>
                        </div>    
                        <div class="col-md-6">
                            @php($name = 'email')
                            @php($label = 'Email')
                            <div class="my-1">
                                <label for="">{{$label}} <i class="text-danger">*</i></label>
                                <input type="email" name="{{$name}}[]" class="form-control cls_required" onChange="getUserByEmail(event,this,'#name')" placeholder="{{$label}}">                      
                                <small class="form-errors pull-right req text-danger" value="*">{{ $errors->first($name,":message") }}</small>
                            </div>
                        </div>    
                        <div class="col-md-4">
                            @php($name = 'name')
                            @php($label = 'Name')
                            <div class="my-1">
                                <label for="">{{$label}} <i class="text-danger">*</i></label>
                                <input type="email" name="{{$name}}" disabled class="form-control" id="name" placeholder="{{$label}}">                      
                                <small class="form-errors pull-right req text-danger" value="*">{{ $errors->first($name,":message") }}</small>
                            </div>
                        </div>  
                        <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-primary mt-4" onclick="add(event)">Add</button>
                        </div>  
                        <div id="new_chq"></div>
                        <input type="hidden" value="1" id="total_chq">
                    </div>    
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_ids" id="user_ids">
                    <button type="button" class="btn btn-secondary" style="background-color: #565e64 !important;" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" style="background-color:#0a58ca !important;" onclick="formSubmitWithModal(event,this,'#md_add_event','#form_md_add_event','#events_table')" content="add event">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>