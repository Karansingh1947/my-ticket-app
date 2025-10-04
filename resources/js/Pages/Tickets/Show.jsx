import React from "react";
import { useForm, Link, router } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Show({ auth, ticket, users = [] }) {
  const { data, setData, patch, processing } = useForm({
    status: ticket.status,
    assigned_to: ticket.assigned_to || "",
  });

  const isAdmin = auth.user.is_admin;
  const isAuthor = auth.user.id === ticket.created_by;
  const isAssignee = auth.user.id === ticket.assigned_to;

  function updateStatus(e) {
    e.preventDefault();
    patch(route("tickets.update", ticket.id));
  }

  function reassignTicket(e) {
    e.preventDefault();
    patch(route("tickets.update", ticket.id));
  }

  function deleteTicket(e) {
    e.preventDefault();
    if (confirm("Are you sure you want to delete this ticket?")) {
      router.delete(route("tickets.destroy", ticket.id));
    }
  }

  return (
    <AuthenticatedLayout user={auth.user}>
      <div className="p-6 max-w-2xl mx-auto">
        <h1 className="text-2xl font-bold mb-4">{ticket.name}</h1>

        {/* --- Description --- */}
        <p className="mb-4 text-gray-700">{ticket.description}</p>

        {/* --- Ticket Info --- */}
        <div className="bg-white shadow rounded-lg p-4 mb-6 border">
          <div className="grid grid-cols-2 gap-4 text-sm">
            <p>
              <strong>Status:</strong> {ticket.status}
            </p>
            <p>
              <strong>Created By:</strong>{" "}
              {ticket.author ? ticket.author.name : "Unknown"}
            </p>
            <p>
              <strong>Assigned To:</strong>{" "}
              {ticket.assignee ? ticket.assignee.name : "Not Assigned"}
            </p>
            <p>
              <strong>Created At:</strong>{" "}
              {new Date(ticket.created_at).toLocaleString()}
            </p>
          </div>

          {ticket.image_path && (
            <div className="mt-4">
              <strong>Attachment:</strong>
              <img
                src={`/storage/${ticket.image_path}`}
                alt="Ticket"
                className="rounded border mt-2 max-w-sm"
              />
            </div>
          )}
        </div>

        {/* --- Admin/Author/Assignee Actions --- */}
        <div className="space-y-4">
          {/* ✅ Admin or Assignee can change status */}
          {(isAssignee || isAdmin) && (
            <form onSubmit={updateStatus} className="space-y-2">
              <label className="block font-semibold">
                Update Status (In Progress / Completed)
              </label>
              <select
                value={data.status}
                onChange={(e) => setData("status", e.target.value)}
                className="w-full border rounded p-2"
              >
                <option value="pending">Pending</option>
                <option value="inprogress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="onhold">On Hold</option>
              </select>
              <button
                type="submit"
                disabled={processing}
                className="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
              >
                Save Status
              </button>
            </form>
          )}

          {/* ✅ Admin can reassign */}
          {isAdmin && (
            <form onSubmit={reassignTicket} className="space-y-2">
              <label className="block font-semibold">Reassign Ticket</label>
              <select
                value={data.assigned_to}
                onChange={(e) => setData("assigned_to", e.target.value)}
                className="w-full border rounded p-2"
              >
                <option value="">Select a user...</option>
                {users.map((user) => (
                  <option key={user.id} value={user.id}>
                    {user.name}
                  </option>
                ))}
              </select>
              <button
                type="submit"
                className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
              >
                Save Assignment
              </button>
            </form>
          )}

          {/* ✅ Author or Admin can delete */}
          {(isAdmin || isAuthor) && (
            <form onSubmit={deleteTicket}>
              <button
                type="submit"
                className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
              >
                Delete Ticket
              </button>
            </form>
          )}
        </div>

        <Link
          href={route("tickets.index")}
          className="block mt-6 text-blue-600 hover:underline"
        >
          ← Back to list
        </Link>
      </div>
    </AuthenticatedLayout>
  );
}
