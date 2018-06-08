<style>
    #modal-iframe-url{
        width: 100%;
        border: none;
    }
</style>
<div class="modal fade text-xs-left" id="modal-iframe" role="dialog" aria-labelledby="myModalLabe" aria-hidden="true">
    <div class="modal-dialog" id="modal-iframe-width" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="card-body">
                    <div class="card-block">
                        <div class="row" style="text-align: center;">
                            <img id="modal-iframe-image" src="{{asset('assets/system/loading.gif')}}">
                            <iframe id="modal-iframe-url" style="display: none;" src=""></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>