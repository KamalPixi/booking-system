<div>
    <section class="page-header page-header-text-light bg-light mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="">Passenger List</h6>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb justify-content-start justify-content-md-end mb-0">
                        <li>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-hand-pointer text-white"></i> Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button class="dropdown-item text-uppercase" type="button">
                                           <i class="fa-solid fa-user-plus"></i> Add Passengers
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="row m-2">
        <div wire:loading>
            <x-loading />
        </div>

        <div class="card px-0 overflow-hidden">

            <div class="card-header row align-items-center">
                <div class="col">
                    <div class="text-bold"></div>
                </div>

                <div class="col">
                    <div class="row">
                        <div class="col px-0">
                            <label for="" class="mb-0 text-small">Filter By</label>
                            <select wire:model="filter_by" class="form-control form-control-sm">
                                <option value="PASSPORT_NO">PassportNo</option>
                                <option value="NAME">Name</option>
                            </select>
                        </div>


                        <div class="col">
                            <label for="" class="mb-0 text-small">Filter Value</label>
                            @if ($filter_by == 'PASSPORT_NO')
                                <input wire:key="{{ uniqid() }}" wire:model="filter_input" placeholder="Enter {{ strtolower(str_replace('_', ' ', $filter_by)) }}" class="form-control form-control-sm" type="text">
                            @endif
                        </div>
                        <div class="col-2 px-0">
                            <label for="" class="mb-0"></label>
                        <button wire:click="resetFilter" class="btn btn-secondary btn-sm d-block p-1 px-2">Reset</button>
                        </div>
                    </div>
                </div>
            </div>


            <div>
                <table class="theme-table table table-sm">
                <thead>
                    <tr class="text-small">
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">PassportNo</th>
                        <th scope="col">PassportExpires</th>
                        <th scope="col">MobileNo</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Nationality</th>
                        <th scope="col">View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($passengers as $k => $passenger)
                    <tr class="text-small" wire:key="{{ uniqid() }}">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>
                            <a target="_blank" href="" class="btn btn-primary btn-sm px-1 py-0">
                                <small>
                                    VIEW
                                </small>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>
        <div class="text-center w-100 mt-3">
            {{ $passengers->links() }}
        </div>
    </div>
</div>
