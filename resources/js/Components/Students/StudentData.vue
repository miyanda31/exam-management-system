<template>
    <table class="table table-bordered table-sm">
        <thead>
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th >Class</th>
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
    name: "StudentData",
}
</script>

