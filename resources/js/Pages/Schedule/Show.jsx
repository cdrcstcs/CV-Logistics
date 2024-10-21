import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import {
  SCHEDULE_PRIORITY_CLASS_MAP,
  SCHEDULE_PRIORITY_TEXT_MAP,
  SCHEDULE_STATUS_CLASS_MAP,
  SCHEDULE_STATUS_TEXT_MAP,
} from "@/constants.jsx";
export default function Show({ auth, schedule }) {
  return (
    <AuthenticatedLayout
      user={auth.user}
      header={
        <div className="flex items-center justify-between">
          <h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {`Schedule "${schedule.name}"`}
          </h2>
          <Link
            href={route("schedule.edit", schedule.id)}
            className="bg-emerald-500 py-1 px-3 text-white rounded shadow transition-all hover:bg-emerald-600"
          >
            Edit
          </Link>
        </div>
      }
    >
      <Head title={`Schedule "${schedule.name}"`} />
      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div>
              <img
                src={schedule.image_path}
                alt=""
                className="w-full h-64 object-cover"
              />
            </div>
            <div className="p-6 text-gray-900 dark:text-gray-100">
              <div className="grid gap-1 grid-cols-2 mt-2">
                <div>
                  <div>
                    <label className="font-bold text-lg">Schedule ID</label>
                    <p className="mt-1">{schedule.id}</p>
                  </div>
                  <div className="mt-4">
                    <label className="font-bold text-lg">Schedule Name</label>
                    <p className="mt-1">{schedule.name}</p>
                  </div>

                  <div className="mt-4">
                    <label className="font-bold text-lg">Schedule Status</label>
                    <p className="mt-1">
                      <span
                        className={
                          "px-2 py-1 rounded text-white " +
                          SCHEDULE_STATUS_CLASS_MAP[schedule.status]
                        }
                      >
                        {SCHEDULE_STATUS_TEXT_MAP[schedule.status]}
                      </span>
                    </p>
                  </div>

                  <div className="mt-4">
                    <label className="font-bold text-lg">Schedule Priority</label>
                    <p className="mt-1">
                      <span
                        className={
                          "px-2 py-1 rounded text-white " +
                          SCHEDULE_PRIORITY_CLASS_MAP[schedule.priority]
                        }
                      >
                        {SCHEDULE_PRIORITY_TEXT_MAP[schedule.priority]}
                      </span>
                    </p>
                  </div>
                  <div className="mt-4">
                    <label className="font-bold text-lg">Created By</label>
                    <p className="mt-1">{schedule.createdBy.name}</p>
                  </div>
                </div>
                <div>
                  <div>
                    <label className="font-bold text-lg">Due Date</label>
                    <p className="mt-1">{schedule.due_date}</p>
                  </div>
                  <div className="mt-4">
                    <label className="font-bold text-lg">Create Date</label>
                    <p className="mt-1">{schedule.created_at}</p>
                  </div>
                  <div className="mt-4">
                    <label className="font-bold text-lg">Updated By</label>
                    <p className="mt-1">{schedule.updatedBy.name}</p>
                  </div>
                  <div className="mt-4">
                    <label className="font-bold text-lg">Shipment</label>
                    <p className="mt-1">
                      <Link
                        href={route("shipment.show", schedule.shipment.id)}
                        className="hover:underline"
                      >
                        {schedule.shipment.name}
                      </Link>
                    </p>
                  </div>
                  <div className="mt-4">
                    <label className="font-bold text-lg">Assigned User</label>
                    <p className="mt-1">{schedule.assignedUser.name}</p>
                  </div>
                </div>
              </div>

              <div className="mt-4">
                <label className="font-bold text-lg">Schedule Description</label>
                <p className="mt-1">{schedule.description}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
