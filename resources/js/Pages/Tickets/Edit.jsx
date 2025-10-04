import React from "react";
import { useForm, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Edit({ auth, ticket, users }) {
  const { data, setData, put, processing, errors } = useForm({
    name: ticket.name || "",
    description: ticket.description || "",
    status: ticket.status || "pending",
    assigned_to: ticket.assigned_to || "",
    image: null,
  });

  function submit(e) {
    e.preventDefault();
    put(route("tickets.update", ticket.id));
  }

  return (
    <AuthenticatedLayout user={auth.user}>
      <div className="p-6 max-w-lg">
        <h1 className="text-xl font-bold mb-4">Edit Ticket</h1>
        <form onSubmit={submit} className="space-y-4">
          <div>
            <label>Name</label>
            <input
              type="text"
              className="w-full border rounded p-2"
              value={data.name}
              onChange={(e) => setData("name", e.target.value)}
            />
            {errors.name && <div className="text-red-600">{errors.name}</div>}
          </div>

          <div>
            <label>Description</label>
            <textarea
              className="w-full border rounded p-2"
              value={data.description}
              onChange={(e) => setData("description", e.target.value)}
            />
          </div>

          <div>
            <label>Status</label>
            <select
              className="w-full border rounded p-2"
              value={data.status}
              onChange={(e) => setData("status", e.target.value)}
            >
              <option value="pending">Pending</option>
              <option value="inprogress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="onhold">On Hold</option>
            </select>
          </div>

          <div>
            <label>Assign To</label>
            <select
              className="w-full border rounded p-2"
              value={data.assigned_to}
              onChange={(e) => setData("assigned_to", e.target.value)}
            >
              <option value="">-- None --</option>
              {users.map((u) => (
                <option key={u.id} value={u.id}>
                  {u.name}
                </option>
              ))}
            </select>
          </div>

          <div>
            <label>Image</label>
            <input
              type="file"
              onChange={(e) => setData("image", e.target.files[0])}
            />
          </div>

          <button
            type="submit"
            disabled={processing}
            className="px-4 py-2 bg-blue-600 text-white rounded"
          >
            Update
          </button>
          <Link href={route("tickets.index")} className="ml-2 text-blue-600">
            Cancel
          </Link>
        </form>
      </div>
    </AuthenticatedLayout>
  );
}
