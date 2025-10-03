import React from "react";
import { useForm, Link } from "@inertiajs/react";

export default function Show({ ticket }) {
  const { data, setData, patch, processing } = useForm({
    status: ticket.status,
  });

  function updateStatus(e) {
    e.preventDefault();
    patch(route("tickets.update", ticket.id));
  }

  return (
    <div className="p-6 max-w-lg">
      <h1 className="text-xl font-bold mb-2">{ticket.name}</h1>
      <p className="mb-2">{ticket.description}</p>
      <p className="text-sm text-gray-600 mb-2">
        Status: <span className="font-semibold">{ticket.status}</span>
      </p>
      {ticket.image_path && (
        <img
          src={`/storage/${ticket.image_path}`}
          alt="Ticket"
          className="mb-4 rounded"
        />
      )}

      {/* Assignee status update */}
      <form onSubmit={updateStatus} className="space-y-2">
        <label>Update Status (if assignee)</label>
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
          className="px-4 py-2 bg-green-600 text-white rounded"
        >
          Save
        </button>
      </form>

      <div className="mt-4">
        <Link href={route("tickets.index")} className="text-blue-600">
          Back to list
        </Link>
      </div>
    </div>
  );
}
