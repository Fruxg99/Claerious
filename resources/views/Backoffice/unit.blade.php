@extends('Template.backoffice')
@section('title', 'Claerious - Products')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Unit List</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddUnit">
        <i class="fas fa-plus fa-sm text-white-50 mr-2"></i> New Unit
    </a>
</div>

<div class="row">

    

</div>

<div class="modal fade" id="modalAddItem" tabindex="-1" role="dialog" aria-labelledby="modalAddItem" aria-hidden="true">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">New Product</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Unit Name</label>
                                <input type="text" class="form-control form-control-user" id="productName">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="btn-reject" onclick="addUnit()">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

    })

    function addUnit() {
        
    }
</script>

@endsection