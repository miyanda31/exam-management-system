<template>
    <table class="table table-bordered table-sm">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Gender</th>
            <slot name="header"/>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <slot name="body" />
        </tbody>
    </table>
</template>

<script>
export default {
    name: "StaffData",
}
</script>
