import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import {
  SHIPMENT_STATUS_CLASS_MAP,
  SHIPMENT_STATUS_TEXT_MAP,
} from "@/constants.jsx";
import SchedulesTable from "../Schedule/SchedulesTable";
export default function Show({ auth, success, shipment, schedules, queryParams }) {
  return (
    <AuthenticatedLayout
      user={auth.user}
      header={
        <div className="flex items-center justify-between">
          <h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {`Shipment "${shipment.name}"`}
          </h2>
          <Link
            href={route("shipment.edit", shipment.id)}
            className="bg-emerald-500 py-1 px-3 text-white rounded shadow transition-all hover:bg-emerald-600"
          >
            Edit
          </Link>
        </div>
      }
    >
      <Head title={`Shipment "${shipment.name}"`} />
      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div>
              <img
                src={shipment.image_path}
                alt=""
                className="w-full h-64 object-cover"
              />
            </div>
            <div className="p-6 text-gray-900 dark:text-gray-100">
              <div className="grid gap-1 grid-cols-2 mt-2">
                <div>
                  <div>
                    <label className="font-bold text-lg">Shipment ID</label>
                    <p className="mt-1">{shipment.id}</p>
                  </div>
                  <div className="mt-4">
                    <label className="font-bold text-lg">Shipment Name</label>
                    <p className="mt-1">{shipment.name}</p>
                  </div>

                  <div className="mt-4">
                    <label className="font-bold text-lg">Shipment Status</label>
                    <p className="mt-1">
                      <span
                        className={
                          "px-2 py-1 rounded text-white " +
                          SHIPMENT_STATUS_CLASS_MAP[shipment.status]
                        }
                      >
                        {SHIPMENT_STATUS_TEXT_MAP[shipment.status]}
                      </span>
                    </p>
                  </div>
                  <div className="mt-4">
                    <label className="font-bold text-lg">Created By</label>
                    <p className="mt-1">{shipment.createdBy.name}</p>
                  </div>
                </div>
                <div>
                  <div>
                    <label className="font-bold text-lg">Due Date</label>
                    <p className="mt-1">{shipment.due_date}</p>
                  </div>
                  <div className="mt-4">
                    <label className="font-bold text-lg">Create Date</label>
                    <p className="mt-1">{shipment.created_at}</p>
                  </div>
                  <div className="mt-4">
                    <label className="font-bold text-lg">Updated By</label>
                    <p className="mt-1">{shipment.updatedBy.name}</p>
                  </div>
                </div>
              </div>

              <div className="mt-4">
                <label className="font-bold text-lg">Shipment Description</label>
                <p className="mt-1">{shipment.description}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="pb-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 text-gray-900 dark:text-gray-100">
              <SchedulesTable
                schedules={schedules}
                success={success}
                queryParams={queryParams}
                hideShipmentColumn={true}
              />
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
